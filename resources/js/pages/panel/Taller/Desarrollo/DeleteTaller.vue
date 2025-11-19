<script setup lang="ts">
import { ref, watch } from 'vue';
import axios, { AxiosError } from 'axios';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import { useToast } from 'primevue/usetoast';

// Interfaces
interface Taller {
    id: number;
    nombre?: string;
    description?: string;
    state?: boolean;
}

// Props y Emit
const props = defineProps<{
  visible: boolean;
  taller: Taller | null;
}>();

const emit = defineEmits<{
    (e: 'update:visible', value: boolean): void;
    (e: 'deleted'): void;
}>();

// Toast
const toast = useToast();

// Ref local
const localVisible = ref<boolean>(false);

// Watchers
watch(() => props.visible, (newVal) => {
    localVisible.value = newVal;
});

// Métodos
function closeDialog() {
    emit('update:visible', false);
}

async function deleteTaller() {
    if (!props.taller) return;

    try {
        await axios.delete(`/taller/${props.taller.id}`);
        emit('deleted');
        closeDialog();
        toast.add({
            severity: 'success',
            summary: 'Éxito',
            detail: 'Taller eliminado correctamente',
            life: 3000
        });
    } catch (error) {
        console.error(error);
        let errorMessage = 'Error eliminando el taller';
        const axiosError = error as AxiosError<any>;
        if (axiosError.response) {
            errorMessage = axiosError.response.data.message || errorMessage;
        }
        toast.add({ severity: 'error', summary: 'Error', detail: errorMessage, life: 3000 });
    }
}
</script>

<template>
<Dialog 
    v-model:visible="localVisible" 
    :style="{ width: '90%', maxWidth: '450px' }" 
    header="Confirmar" 
    :modal="true"
    @update:visible="closeDialog"
>
    <div class="flex items-center gap-4">
        <i class="pi pi-exclamation-triangle !text-3xl" />
        <span v-if="props.taller">
            ¿Estás seguro de eliminar el taller llamado: 
            <b>{{ props.taller.nombre }}</b>?
        </span>
    </div>
    <template #footer>
        <Button label="No" icon="pi pi-times" text @click="closeDialog" />
        <Button label="Sí" icon="pi pi-check" @click="deleteTaller" />
    </template>
</Dialog>
</template>
