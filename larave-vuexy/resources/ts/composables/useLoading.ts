import { ref } from 'vue';

export default function usePreload() {
    const loading = ref(false);

    const isLoading = (value = false) => {
        loading.value = value;
    };

    return {
        loading,
        isLoading
    };
}
