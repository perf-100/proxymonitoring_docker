import { ref, onMounted, onBeforeUnmount } from 'vue';

export function useProxyChannel(proxies) {
    const channelName = 'proxies';

    const subscribe = () => {
        if (!window.Echo) return;

        window.Echo.channel(channelName)
            .listen('.proxy.updated', (e) => {
                const updatedProxy = e.proxy;
                const index = proxies.value.data.findIndex(p => p.id === updatedProxy.id);
                if (index !== -1) {
                    proxies.value.data[index] = { ...proxies.value.data[index], ...updatedProxy };
                }
            });
    };

    const unsubscribe = () => {
        if (window.Echo) window.Echo.leave(channelName);
    };

    onMounted(subscribe);
    onBeforeUnmount(unsubscribe);
}