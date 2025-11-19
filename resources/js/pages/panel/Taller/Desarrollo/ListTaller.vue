<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import Tag from 'primevue/tag';
import Select from 'primevue/select';
import axios from 'axios';
import { debounce } from 'lodash';
import { useToast } from 'primevue/usetoast';
import DeleteTaller from './DeleteTaller.vue';
import UpdateTaller from './UpdateTaller.vue';

// Interfaces
interface Taller {
  id: number;
  nombre: string;
  turno: string;
  hora_inicio: string;
  hora_fin: string;
  sede: string;
  fecha_inicio: string;
  fecha_fin: string;
  capacidad_alumnos: number;
  descripcion: string;
  requisitos: string;
  temario: string;
  estado: string;
  creacion?: string;
  actualizacion?: string;
}

interface FiltroOption {
  name: string;
  value: string | number;
}

// Refs
const dt = ref<any>(null);
const talleres = ref<Taller[]>([]);
const selectedTalleres = ref<Taller[] | null>(null);
const loading = ref(false);
const globalFilterValue = ref('');
const deleteTallerDialog = ref(false);
const updateTallerDialog = ref(false);
const taller = ref<Taller | null>(null);
const selectedTallerId = ref<number | null>(null);

// Filtros
const selectedEstado = ref<FiltroOption | null>(null);
const selectedTurno = ref<FiltroOption | null>(null);

// Props
const props = defineProps<{ refresh: number }>();
const toast = useToast();

// Opciones
const estadoOptions = ref<FiltroOption[]>([
  { name: 'TODOS', value: '' },
  { name: 'DISPONIBLES', value: 'disponible' },
  { name: 'LLENOS', value: 'lleno' },
]);

const turnoOptions = ref<FiltroOption[]>([
  { name: 'TODOS', value: '' },
  { name: 'MAÑANA', value: 'mañana' },
  { name: 'TARDE', value: 'tarde' },
]);

// Watchers
watch(() => props.refresh, () => loadTalleres());
watch([selectedEstado, selectedTurno], () => {
  pagination.value.currentPage = 1;
  loadTalleres();
});

// Paginación
const pagination = ref({ currentPage: 1, perPage: 15, total: 0 });

// Eventos
function editTaller(t: Taller) {
  selectedTallerId.value = t.id ?? null;
  updateTallerDialog.value = true;
}

function confirmDeleteTaller(selected: Taller) {
  if (!selected.id) return;
  taller.value = selected;
  deleteTallerDialog.value = true;
}

function handleTallerUpdated() {
  loadTalleres();
}

function handleTallerDeleted() {
  loadTalleres();
}

// Cargar talleres desde backend
const loadTalleres = async () => {
  loading.value = true;

  try {
    const params: any = {
      page: pagination.value.currentPage,
      per_page: pagination.value.perPage,
      search: globalFilterValue.value,
      state: selectedEstado.value?.value || selectedTurno.value?.value,
    };

    const response = await axios.get('/taller', { params });

    talleres.value = response.data.data;
    pagination.value.currentPage = response.data.meta.current_page;
    pagination.value.total = response.data.meta.total;
  } catch (error) {
    console.error('Error al cargar los talleres:', error);
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'No se pudieron cargar los talleres',
      life: 3000
    });
  } finally {
    loading.value = false;
  }
};

const onPage = (event: { page: number; rows: number }) => {
  pagination.value.currentPage = event.page + 1;
  pagination.value.perPage = event.rows;
  loadTalleres();
};

const getEstadoSeverity = (estado: string) => {
  return estado === 'disponible' ? 'success' : 'danger';
};

const onGlobalSearch = debounce(() => {
  pagination.value.currentPage = 1;
  loadTalleres();
}, 500);

onMounted(() => {
  loadTalleres();
});
</script>

<template>
<DataTable
  ref="dt"
  v-model:selection="selectedTalleres"
  :value="talleres"
  dataKey="id"
  :paginator="true"
  :rows="pagination.perPage"
  :totalRecords="pagination.total"
  :loading="loading"
  :lazy="true"
  @page="onPage"
  :rowsPerPageOptions="[15, 20, 25]"
  scrollable
  scrollDirection="both"
  responsiveLayout="scroll"
  class="w-full text-sm sm:text-base"
  paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
  currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} talleres"
>

  <!-- Header -->
  <template #header>
    <div class="flex flex-col md:flex-row gap-2 items-start md:items-center justify-between w-full">
      <h4 class="m-0 text-base sm:text-lg md:text-xl">GESTIÓN DE TALLERES</h4>

      <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
        <!-- Buscador -->
        <IconField class="w-full sm:w-64">
          <InputIcon><i class="pi pi-search" /></InputIcon>
          <InputText
            v-model="globalFilterValue"
            @input="onGlobalSearch"
            placeholder="Buscar taller..."
            class="w-full"
          />
        </IconField>

        <!-- Filtro estado -->
        <Select
          v-model="selectedEstado"
          :options="estadoOptions"
          optionLabel="name"
          placeholder="Estado"
          class="w-full sm:w-40"
        />

        <!-- Filtro turno -->
        <Select
          v-model="selectedTurno"
          :options="turnoOptions"
          optionLabel="name"
          placeholder="Turno"
          class="w-full sm:w-40"
        />

        <Button title="Refrescar" icon="pi pi-refresh" outlined rounded class="w-full sm:w-auto"
          @click="loadTalleres" />
      </div>
    </div>
  </template>

  <!-- Selección -->
  <Column selectionMode="multiple" style="width: 1rem" />

  <!-- Columnas principales -->
  <Column field="nombre" header="Nombre" sortable style="min-width: 14rem" />
  <Column field="turno" header="Turno" sortable style="min-width: 8rem" />
  <Column field="sede" header="Sede" sortable style="min-width: 12rem" />

  <Column field="hora_inicio" header="Hora Inicio" sortable style="min-width: 10rem" />
  <Column field="hora_fin" header="Hora Fin" sortable style="min-width: 10rem" />

  <Column field="fecha_inicio" header="Inicio" sortable style="min-width: 10rem" />
  <Column field="fecha_fin" header="Fin" sortable style="min-width: 10rem" />

  <Column field="capacidad_alumnos" header="Capacidad" sortable style="min-width: 8rem" />

  <!-- Estado -->
  <Column field="estado" header="Estado" sortable style="min-width: 10rem">
    <template #body="{ data }">
      <Tag :value="data.estado" :severity="getEstadoSeverity(data.estado)" />
    </template>
  </Column>

  <!-- Acciones -->
  <Column header="Acciones" style="min-width: 10rem">
    <template #body="slotProps">
      <Button title="Editar" icon="pi pi-pencil" outlined rounded class="mr-2"
        @click="editTaller(slotProps.data)" />

      <Button title="Eliminar" icon="pi pi-trash" outlined rounded severity="danger"
        @click="confirmDeleteTaller(slotProps.data)" />
    </template>
  </Column>

</DataTable>

<!-- Modales -->
<DeleteTaller
  v-model:visible="deleteTallerDialog"
  :taller="taller"
  @deleted="handleTallerDeleted"
/>

<UpdateTaller
  v-model:visible="updateTallerDialog"
  :tallerId="selectedTallerId"
  @updated="handleTallerUpdated"
/>
</template>
