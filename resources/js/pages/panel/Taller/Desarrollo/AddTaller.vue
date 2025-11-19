<script setup lang="ts">
import { ref } from 'vue';
import axios, { AxiosError } from 'axios';
import Dialog from 'primevue/dialog';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Select from 'primevue/select';
import Calendar from 'primevue/calendar';
import { useToast } from 'primevue/usetoast';

interface TallerForm {
    nombre: string;
    turno: string;
    hora_inicio: string;
    hora_fin: string;
    sede: string;
    fecha_inicio: Date | null;  // <-- SOLO DATE
    fecha_fin: Date | null;     // <-- SOLO DATE
    capacidad_alumnos: string | null; // <-- STRING
    descripcion: string;
    requisitos: string;
    temario: string;
    estado: string;
}

interface ServerErrors {
    [key: string]: string[];
}

const toast = useToast();
const submitted = ref(false);
const tallerDialog = ref(false);
const serverErrors = ref<ServerErrors>({});

const taller = ref<TallerForm>({
    nombre: '',
    turno: '',
    hora_inicio: '',
    hora_fin: '',
    sede: '',
    fecha_inicio: null,
    fecha_fin: null,
    capacidad_alumnos: null,
    descripcion: '',
    requisitos: '',
    temario: '',
    estado: 'disponible'
});

const turnoOptions = [
    { label: "Mañana", value: "mañana" },
    { label: "Tarde", value: "tarde" }
];

const emit = defineEmits<{ (e: 'taller-agregado'): void }>();

function resetTaller() {
    taller.value = {
        nombre: '',
        turno: '',
        hora_inicio: '',
        hora_fin: '',
        sede: '',
        fecha_inicio: null,
        fecha_fin: null,
        capacidad_alumnos: null,
        descripcion: '',
        requisitos: '',
        temario: '',
        estado: 'disponible'
    };
    serverErrors.value = {};
    submitted.value = false;
}

function openNew() {
    resetTaller();
    tallerDialog.value = true;
}

function hideDialog() {
    tallerDialog.value = false;
    resetTaller();
}

async function guardarTaller() {
    submitted.value = true;
    serverErrors.value = {};

    try {
        // Convertir correctamente antes de enviar
        const payload = {
            ...taller.value,
            fecha_inicio: taller.value.fecha_inicio
                ? taller.value.fecha_inicio.toISOString().split('T')[0]
                : null,
            fecha_fin: taller.value.fecha_fin
                ? taller.value.fecha_fin.toISOString().split('T')[0]
                : null,
            capacidad_alumnos: taller.value.capacidad_alumnos
                ? Number(taller.value.capacidad_alumnos)
                : null
        };

        await axios.post('/taller', payload);

        toast.add({ severity: 'success', summary: 'Éxito', detail: 'Taller registrado', life: 3000 });
        hideDialog();
        emit('taller-agregado');

    } catch (error) {
        const axiosError = error as AxiosError;
        if (axiosError.response && axiosError.response.status === 422) {
            serverErrors.value = (axiosError.response.data as any).errors;
        } else {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'No se pudo registrar el taller',
                life: 3000
            });
        }
    }
}
</script>

<template>
<Toolbar class="mb-6">
    <template #start>
        <Button label="Nuevo Taller" icon="pi pi-plus" class="mr-2" @click="openNew" />
    </template>
</Toolbar>

<Dialog 
    v-model:visible="tallerDialog"
    :style="{ width: '95vw', maxWidth: '750px' }"
    header="Registro de Taller"
    :modal="true"
>
    <div class="grid grid-cols-12 gap-4">

        <!-- Nombre -->
        <div class="col-span-12 md:col-span-8">
            <label class="font-bold mb-2 block">Nombre <span class="text-red-500">*</span></label>
            <InputText v-model="taller.nombre" class="w-full" />
            <small v-if="submitted && !taller.nombre" class="text-red-500">El nombre es obligatorio.</small>
            <small v-if="serverErrors.nombre" class="text-red-500">{{ serverErrors.nombre[0] }}</small>
        </div>

        <!-- Turno -->
        <div class="col-span-12 md:col-span-4">
            <label class="font-bold mb-2 block">Turno <span class="text-red-500">*</span></label>
            <Select v-model="taller.turno" :options="turnoOptions" optionLabel="label" optionValue="value" class="w-full" />
            <small v-if="serverErrors.turno" class="text-red-500">{{ serverErrors.turno[0] }}</small>
        </div>

        <!-- Horarios -->
        <div class="col-span-12 md:col-span-6">
            <label class="font-bold mb-2 block">Hora inicio *</label>
            <InputText type="time" v-model="taller.hora_inicio" class="w-full" />
            <small v-if="serverErrors.hora_inicio" class="text-red-500">{{ serverErrors.hora_inicio[0] }}</small>
        </div>

        <div class="col-span-12 md:col-span-6">
            <label class="font-bold mb-2 block">Hora fin *</label>
            <InputText type="time" v-model="taller.hora_fin" class="w-full" />
            <small v-if="serverErrors.hora_fin" class="text-red-500">{{ serverErrors.hora_fin[0] }}</small>
        </div>

        <!-- Sede -->
        <div class="col-span-12">
            <label class="font-bold mb-2 block">Sede *</label>
            <InputText v-model="taller.sede" class="w-full" />
            <small v-if="serverErrors.sede" class="text-red-500">{{ serverErrors.sede[0] }}</small>
        </div>

        <!-- Fechas -->
        <div class="col-span-12 md:col-span-6">
            <label class="font-bold mb-2 block">Fecha inicio *</label>
            <Calendar v-model="taller.fecha_inicio" dateFormat="yy-mm-dd" class="w-full" />
            <small v-if="serverErrors.fecha_inicio" class="text-red-500">{{ serverErrors.fecha_inicio[0] }}</small>
        </div>

        <div class="col-span-12 md:col-span-6">
            <label class="font-bold mb-2 block">Fecha fin *</label>
            <Calendar v-model="taller.fecha_fin" dateFormat="yy-mm-dd" class="w-full" />
            <small v-if="serverErrors.fecha_fin" class="text-red-500">{{ serverErrors.fecha_fin[0] }}</small>
        </div>

        <!-- Capacidad -->
        <div class="col-span-12 md:col-span-4">
            <label class="font-bold mb-2 block">Capacidad *</label>
            <InputText type="number" v-model="taller.capacidad_alumnos" class="w-full" />
            <small v-if="serverErrors.capacidad_alumnos" class="text-red-500">{{ serverErrors.capacidad_alumnos[0] }}</small>
        </div>

        <!-- Descripción -->
        <div class="col-span-12">
            <label class="font-bold mb-2 block">Descripción</label>
            <Textarea v-model="taller.descripcion" rows="3" class="w-full" autoResize />
        </div>

        <!-- Requisitos -->
        <div class="col-span-12">
            <label class="font-bold mb-2 block">Requisitos</label>
            <Textarea v-model="taller.requisitos" rows="3" class="w-full" autoResize />
        </div>

        <!-- Temario -->
        <div class="col-span-12">
            <label class="font-bold mb-2 block">Temario</label>
            <Textarea v-model="taller.temario" rows="5" class="w-full" autoResize />
        </div>
    </div>

    <!-- Footer -->
    <template #footer>
        <Button label="Cancelar" icon="pi pi-times" text @click="hideDialog" />
        <Button label="Guardar" icon="pi pi-check" @click="guardarTaller" />
    </template>
</Dialog>
</template>
