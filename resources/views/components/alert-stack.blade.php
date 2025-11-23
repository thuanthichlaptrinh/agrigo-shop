@props(['messages' => []])

@php
	$normalizedAlerts = collect($messages ?? [])
		->map(function ($alert) {
			if (is_string($alert)) {
				return ['message' => $alert, 'type' => 'info'];
			}

			if (!is_array($alert)) {
				return null;
			}

			return [
				'message' => $alert['message'] ?? null,
				'type' => $alert['type'] ?? 'info',
				'title' => $alert['title'] ?? null,
				'timeout' => $alert['timeout'] ?? null,
				'dismissible' => array_key_exists('dismissible', $alert)
					? (bool) $alert['dismissible']
					: true,
				'icon' => $alert['icon'] ?? null,
			];
		})
		->filter(fn ($alert) => filled($alert['message'] ?? null))
		->values()
		->all();
@endphp

<div class="ui-toast-stack" data-toast-stack aria-live="polite" aria-atomic="true"></div>

@once
<style>
	.ui-toast-stack {
		position: fixed;
		top: 24px;
		right: 24px;
		display: flex;
		flex-direction: column;
		gap: 12px;
		align-items: flex-end;
		z-index: 1200;
		pointer-events: none;
	}

	.ui-toast {
		--toast-accent: #2563eb;
		--toast-icon-bg: rgba(37, 99, 235, 0.15);
		--toast-icon-color: #1d4ed8;
		width: min(360px, 92vw);
		background: #fff;
		border-radius: 18px;
		padding: 16px 20px 16px 28px;
		display: flex;
		align-items: flex-start;
		gap: 14px;
		box-shadow: 0 22px 70px rgba(15, 23, 42, 0.2);
		border: 1px solid rgba(15, 23, 42, 0.06);
		position: relative;
		pointer-events: auto;
		overflow: hidden;
		animation: toastSlideIn 0.28s cubic-bezier(0.16, 1, 0.3, 1);
	}

	.ui-toast::before {
		content: '';
		position: absolute;
		inset: 0;
		width: 6px;
		background: var(--toast-accent);
	}

	.ui-toast__icon {
		width: 42px;
		height: 42px;
		border-radius: 50%;
		background: var(--toast-icon-bg);
		color: var(--toast-icon-color);
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 20px;
		flex-shrink: 0;
	}

	.ui-toast__body {
		display: flex;
		flex-direction: column;
		gap: 6px;
		flex: 1;
		min-width: 0;
	}

	.ui-toast__title {
		font-size: 15px;
		font-weight: 700;
		color: #0f172a;
		margin-top: -2px;
	}

	.ui-toast__message {
		font-size: 14px;
		color: #475569;
		line-height: 1.45;
		word-break: break-word;
	}

	.ui-toast__close {
		background: transparent;
		border: none;
		color: #94a3b8;
		font-size: 18px;
		cursor: pointer;
		padding: 2px 4px;
		line-height: 1;
		transition: color 0.15s ease;
	}

	.ui-toast__close:hover {
		color: #475569;
	}

	.ui-toast.is-hiding {
		animation: toastSlideOut 0.22s ease forwards;
	}

	.ui-toast--success {
		--toast-accent: #16a34a;
		--toast-icon-bg: rgba(34, 197, 94, 0.16);
		--toast-icon-color: #15803d;
	}

	.ui-toast--info {
		--toast-accent: #2563eb;
		--toast-icon-bg: rgba(37, 99, 235, 0.18);
		--toast-icon-color: #1d4ed8;
	}

	.ui-toast--warning {
		--toast-accent: #f97316;
		--toast-icon-bg: rgba(249, 115, 22, 0.18);
		--toast-icon-color: #c2410c;
	}

	.ui-toast--error {
		--toast-accent: #dc2626;
		--toast-icon-bg: rgba(220, 38, 38, 0.18);
		--toast-icon-color: #b91c1c;
	}

	@keyframes toastSlideIn {
		from {
			opacity: 0;
			transform: translateX(16px) translateY(-10px);
		}
		to {
			opacity: 1;
			transform: translateX(0) translateY(0);
		}
	}

	@keyframes toastSlideOut {
		from {
			opacity: 1;
			transform: translateX(0) translateY(0);
		}
		to {
			opacity: 0;
			transform: translateX(12px) translateY(-10px);
		}
	}

	@media (max-width: 575px) {
		.ui-toast-stack {
			right: 12px;
			left: 12px;
			align-items: stretch;
		}

		.ui-toast {
			width: 100%;
		}
	}
</style>
@endonce

@once
<script>
(function () {
	const STORAGE_KEY = 'app.toast.queue';
	const presetAlerts = @json($normalizedAlerts);

	const typeConfig = {
		success: {
			icon: 'ri-checkbox-circle-line',
			accent: '#16a34a',
			iconBg: 'rgba(34, 197, 94, 0.16)',
			iconColor: '#15803d',
			defaultTitle: 'Thành công',
			timeout: 1400,
		},
		info: {
			icon: 'ri-information-line',
			accent: '#2563eb',
			iconBg: 'rgba(37, 99, 235, 0.18)',
			iconColor: '#1d4ed8',
			defaultTitle: 'Thông báo',
			timeout: 1400,
		},
		warning: {
			icon: 'ri-error-warning-line',
			accent: '#f97316',
			iconBg: 'rgba(249, 115, 22, 0.2)',
			iconColor: '#c2410c',
			defaultTitle: 'Cảnh báo',
			timeout: 2800,
		},
		error: {
			icon: 'ri-close-circle-line',
			accent: '#dc2626',
			iconBg: 'rgba(220, 38, 38, 0.2)',
			iconColor: '#b91c1c',
			defaultTitle: 'Có lỗi',
			timeout: 2800,
		},
	};

	const safeType = (type) => (type && typeConfig[type]) ? type : 'info';

	const hideToast = (toast) => {
		if (!toast || toast.classList.contains('is-hiding')) {
			return;
		}
		toast.classList.add('is-hiding');
		toast.addEventListener('animationend', () => toast.remove(), { once: true });
	};

	const createToastElement = (payload) => {
		const container = document.querySelector('[data-toast-stack]');
		if (!container) {
			window.alert(payload.message || 'Thông báo');
			return null;
		}

		const type = safeType(payload.type);
		const config = typeConfig[type];

		const toast = document.createElement('div');
		toast.className = `ui-toast ui-toast--${type}`;
		toast.style.setProperty('--toast-accent', config.accent);
		toast.style.setProperty('--toast-icon-bg', config.iconBg);
		toast.style.setProperty('--toast-icon-color', config.iconColor);

		const iconWrap = document.createElement('span');
		iconWrap.className = 'ui-toast__icon';
		if (payload.icon !== false) {
			const icon = document.createElement('i');
			icon.className = payload.icon || config.icon;
			icon.setAttribute('aria-hidden', 'true');
			iconWrap.appendChild(icon);
		}
		toast.appendChild(iconWrap);

		const body = document.createElement('div');
		body.className = 'ui-toast__body';

		const title = document.createElement('div');
		title.className = 'ui-toast__title';
		title.textContent = payload.title || config.defaultTitle;
		body.appendChild(title);

		const message = document.createElement('div');
		message.className = 'ui-toast__message';
		message.textContent = payload.message;
		body.appendChild(message);

		toast.appendChild(body);

		if (payload.dismissible) {
			const closeBtn = document.createElement('button');
			closeBtn.type = 'button';
			closeBtn.className = 'ui-toast__close';
			closeBtn.setAttribute('aria-label', 'Đóng thông báo');
			closeBtn.innerHTML = '&times;';
			toast.appendChild(closeBtn);
		}

		container.appendChild(toast);

		if (payload.timeout && Number.isFinite(payload.timeout)) {
			setTimeout(() => hideToast(toast), payload.timeout);
		}

		return toast;
	};

	document.addEventListener('click', (event) => {
		const closeBtn = event.target.closest('.ui-toast__close');
		if (!closeBtn) {
			return;
		}
		hideToast(closeBtn.closest('.ui-toast'));
	});

	const buildPayload = (message, options = {}) => {
		const type = safeType(options.type);
		const config = typeConfig[type];

		return {
			type,
			message,
			title: options.title ?? config.defaultTitle,
			icon: options.icon ?? config.icon,
			timeout: typeof options.timeout === 'number' ? options.timeout : config.timeout,
			dismissible: options.dismissible !== false,
		};
	};

	const readQueue = () => {
		try {
			const cached = sessionStorage.getItem(STORAGE_KEY);
			if (!cached) {
				return [];
			}
			sessionStorage.removeItem(STORAGE_KEY);
			const parsed = JSON.parse(cached);
			return Array.isArray(parsed) ? parsed : [parsed];
		} catch (error) {
			sessionStorage.removeItem(STORAGE_KEY);
			return [];
		}
	};

	const queuePayload = (payload) => {
		if (!payload?.message) {
			return;
		}
		try {
			const cached = sessionStorage.getItem(STORAGE_KEY);
			const parsed = cached ? JSON.parse(cached) : [];
			const next = Array.isArray(parsed) ? parsed : [parsed];
			next.push(payload);
			sessionStorage.setItem(STORAGE_KEY, JSON.stringify(next));
		} catch (error) {
			sessionStorage.setItem(STORAGE_KEY, JSON.stringify([payload]));
		}
	};

	window.AppAlert = window.AppAlert || {};
	window.AppAlert.show = (message, options = {}) => {
		if (!message) {
			return null;
		}
		const payload = buildPayload(message, options);
		return createToastElement(payload);
	};

	window.AppAlert.queue = (payload) => queuePayload(payload);

	if (Array.isArray(presetAlerts)) {
		presetAlerts.forEach((alert) => window.AppAlert.show(alert.message, alert));
	}

	readQueue().forEach((queued) => {
		if (queued?.message) {
			window.AppAlert.show(queued.message, queued);
		}
	});
})();
</script>
@endonce
