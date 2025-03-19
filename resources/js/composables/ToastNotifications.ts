import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { toast } from 'vue3-toastify';

const toasts = ref();

const reToasted = ref(false);

function fireToast(notification: any, sticky = false) {
    toast(notification.message, {
        toastId: notification.id,
        type: notification.type,
        newestOnTop: true,
        theme: toast.THEME.COLORED,
        autoClose: !sticky && (notification.type == 'success' ? 5000 : false),
        closeOnClick: !sticky,
        onClose: () => (reToasted.value = false),
    });
}

function fireToasts(sticky = false) {
    toasts.value?.forEach((notification: any) => fireToast(notification, sticky));
}

function toastAgain() {
    reToasted.value ? toast.remove() : fireToasts(true);
    reToasted.value = !reToasted.value;
}

watch(
    // NOTE: Since Inertia.js 1.0.1, usePage() may return null initially.
    () => usePage<ShareData>()?.props?.flash.toasts,
    (newToasts) => {
        reToasted.value = false;
        toasts.value = newToasts;
        fireToasts();
    },
);

export function useToasts() {
    return {
        toasts,
        toastAgain,
        reToasted,
    };
}
