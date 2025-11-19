<script setup lang="ts">
import { ref, watch } from "vue";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Select from "primevue/select";
import Calendar from "primevue/datepicker";
import Button from "primevue/button";
import Tag from "primevue/tag";
import axios, { AxiosError } from "axios";
import { useToast } from "primevue/usetoast";
import InputNumber from "primevue/inputnumber";

interface Taller {
    nombre: string;
    turno: string;
    hora_inicio: string;
    hora_fin: string;
    sede: string;
    fecha_inicio: Date | null;
    fecha_fin: Date | null;
    capacidad_alumnos: number | null;
    descripcion: string | null;
    requisitos: string | null;
    temario: string | null;
    estado: string;
    creacion?: string;
    actualizacion?: string;
}

interface ServerErrors {
    [key: string]: string[];
}

const props = defineProps<{
    visible: boolean;
    tallerId: number | null;
}>();

const emit = defineEmits<{
    (e: "update:visible", value: boolean): void;
    (e: "updated"): void;
}>();

const toast = useToast();

const dialogVisible = ref<boolean>(props.visible);
const submitted = ref(false);
const loading = ref(false);

const taller = ref<Taller>({
    nombre: "",
    turno: "",
    hora_inicio: "",
    hora_fin: "",
    sede: "",
    fecha_inicio: null,
    fecha_fin: null,
    capacidad_alumnos: null,
    descripcion: "",
    requisitos: "",
    temario: "",
    estado: "",
});

const turnos = [
    { label: "Mañana", value: "mañana" },
    { label: "Tarde", value: "tarde" },
];

const serverErrors = ref<ServerErrors>({});

// WATCH: abrir modal
watch(
    () => props.visible,
    (val) => {
        dialogVisible.value = val;
        if (val && props.tallerId) fetchTaller();
    }
);

// Sincronizar v-model
watch(dialogVisible, (val) => emit("update:visible", val));

// Obtener taller
const fetchTaller = async () => {
    loading.value = true;
    serverErrors.value = {};

    try {
        const res = await axios.get(`/taller/${props.tallerId}`);
        const data = res.data.taller;

        // Corregimos las fechas para evitar retroceso
        const parseDate = (dateStr: string | null) =>
            dateStr ? new Date(dateStr + "T00:00:00") : null;

        taller.value = {
            nombre: data.nombre,
            turno: data.turno,
            hora_inicio: data.hora_inicio,
            hora_fin: data.hora_fin,
            sede: data.sede,
            fecha_inicio: parseDate(data.fecha_inicio),
            fecha_fin: parseDate(data.fecha_fin),
            capacidad_alumnos: data.capacidad_alumnos,
            descripcion: data.descripcion,
            requisitos: data.requisitos,
            temario: data.temario,
            estado: data.estado,
            creacion: data.creacion,
            actualizacion: data.actualizacion,
        };
    } catch (e) {
        console.error(e);
        toast.add({
            severity: "error",
            summary: "Error",
            detail: "No se pudo cargar el taller",
            life: 3000,
        });
    } finally {
        loading.value = false;
    }
};

// Actualizar taller
const updateTaller = async () => {
    submitted.value = true;
    serverErrors.value = {};

    try {
        const payload = {
            nombre: taller.value.nombre,
            turno: taller.value.turno,
            hora_inicio: taller.value.hora_inicio,
            hora_fin: taller.value.hora_fin,
            sede: taller.value.sede,
            fecha_inicio: taller.value.fecha_inicio,
            fecha_fin: taller.value.fecha_fin,
            capacidad_alumnos: taller.value.capacidad_alumnos,
            descripcion: taller.value.descripcion,
            requisitos: taller.value.requisitos,
            temario: taller.value.temario,
        };

        await axios.put(`/taller/${props.tallerId}`, payload);

        toast.add({
            severity: "success",
            summary: "Actualizado",
            detail: "Taller actualizado correctamente",
            life: 3000,
        });

        dialogVisible.value = false;
        emit("updated");
    } catch (err) {
        const error = err as AxiosError<any>;

        if (error.response?.data?.errors) {
            serverErrors.value = error.response.data.errors;
            toast.add({
                severity: "error",
                summary: "Error de validación",
                detail: "Revisa los campos e intenta nuevamente.",
                life: 5000,
            });
        } else {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "No se pudo actualizar el taller",
                life: 3000,
            });
        }
    }
};
</script>

<template>
<Dialog 
    v-model:visible="dialogVisible"
    modal
    header="Editar Taller"
    :style="{ width: '95vw', maxWidth: '800px' }"
>
    <div class="grid grid-cols-12 gap-4">
        
        <!-- Nombre -->
        <div class="col-span-12">
            <label class="font-bold">Nombre *</label>
            <InputText v-model="taller.nombre" class="w-full" :class="{ 'p-invalid': serverErrors.nombre }" />
            <small v-if="serverErrors.nombre" class="text-red-500">{{ serverErrors.nombre[0] }}</small>
        </div>

        <!-- Turno -->
        <div class="col-span-12 md:col-span-4">
            <label class="font-bold">Turno *</label>
            <Select v-model="taller.turno" :options="turnos" optionLabel="label" optionValue="value" class="w-full"
                    :class="{ 'p-invalid': serverErrors.turno }" />
            <small v-if="serverErrors.turno" class="text-red-500">{{ serverErrors.turno[0] }}</small>
        </div>

        <!-- Hora inicio -->
        <div class="col-span-6 md:col-span-4">
            <label class="font-bold">Hora de inicio *</label>
            <InputText v-model="taller.hora_inicio" placeholder="HH:MM" class="w-full"
                       :class="{ 'p-invalid': serverErrors.hora_inicio }" />
            <small v-if="serverErrors.hora_inicio" class="text-red-500">{{ serverErrors.hora_inicio[0] }}</small>
        </div>

        <!-- Hora fin -->
        <div class="col-span-6 md:col-span-4">
            <label class="font-bold">Hora de fin *</label>
            <InputText v-model="taller.hora_fin" placeholder="HH:MM" class="w-full"
                       :class="{ 'p-invalid': serverErrors.hora_fin }" />
            <small v-if="serverErrors.hora_fin" class="text-red-500">{{ serverErrors.hora_fin[0] }}</small>
        </div>

        <!-- Sede -->
        <div class="col-span-12 md:col-span-6">
            <label class="font-bold">Sede *</label>
            <InputText v-model="taller.sede" class="w-full" :class="{ 'p-invalid': serverErrors.sede }" />
            <small v-if="serverErrors.sede" class="text-red-500">{{ serverErrors.sede[0] }}</small>
        </div>

        <!-- Fecha inicio -->
        <div class="col-span-12 md:col-span-3">
            <label class="font-bold">Fecha inicio *</label>
            <Calendar v-model="taller.fecha_inicio" showIcon class="w-full"
                      :class="{ 'p-invalid': serverErrors.fecha_inicio }" />
            <small v-if="serverErrors.fecha_inicio" class="text-red-500">{{ serverErrors.fecha_inicio[0] }}</small>
        </div>

        <!-- Fecha fin -->
        <div class="col-span-12 md:col-span-3">
            <label class="font-bold">Fecha fin *</label>
            <Calendar v-model="taller.fecha_fin" showIcon class="w-full"
                      :class="{ 'p-invalid': serverErrors.fecha_fin }" />
            <small v-if="serverErrors.fecha_fin" class="text-red-500">{{ serverErrors.fecha_fin[0] }}</small>
        </div>

        <!-- Capacidad -->
        <div class="col-span-12 md:col-span-3">
            <label class="font-bold">Capacidad *</label>
            <InputNumber
                v-model="taller.capacidad_alumnos"
                :min="1"
                :max="50"
                class="w-full"
                :class="{ 'p-invalid': serverErrors.capacidad_alumnos }"
            />
            <small v-if="serverErrors.capacidad_alumnos" class="text-red-500">
                {{ serverErrors.capacidad_alumnos[0] }}
            </small>
        </div>

        <!-- Estado -->
        <div class="col-span-12 md:col-span-3">
            <label class="font-bold">Estado</label>
            <Tag :value="taller.estado" :severity="taller.estado === 'disponible' ? 'success' : 'danger'" />
        </div>

        <!-- Descripcion -->
        <div class="col-span-12">
            <label class="font-bold">Descripción</label>
            <Textarea v-model="taller.descripcion" rows="3" class="w-full" />
        </div>

        <!-- Requisitos -->
        <div class="col-span-12">
            <label class="font-bold">Requisitos</label>
            <Textarea v-model="taller.requisitos" rows="3" class="w-full" />
        </div>

        <!-- Temario -->
        <div class="col-span-12">
            <label class="font-bold">Temario</label>
            <Textarea v-model="taller.temario" rows="3" class="w-full" />
        </div>
    </div>

    <!-- FOOTER -->
    <template #footer>
        <div class="flex justify-end gap-2">
            <Button label="Cancelar" icon="pi pi-times" text @click="dialogVisible = false" />
            <Button label="Guardar" icon="pi pi-check" :loading="loading" @click="updateTaller" />
        </div>
    </template>
</Dialog>
</template>
