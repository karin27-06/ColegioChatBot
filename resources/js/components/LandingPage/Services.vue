<template>
  <section 
    id="servicios"
    class="flex flex-col items-center justify-center min-h-screen bg-white dark:bg-gray-900 px-6 py-16"
  >
    <h2 class="text-3xl font-bold text-blue-700 dark:text-cyan-400 mb-10 text-center">
      Funcionalidades del Sistema de Gestión e Inscripciones INIF 48.
    </h2>

    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 max-w-6xl w-full">
      <div 
        v-for="(s, i) in servicios" :key="i"
        class="p-6 bg-gray-50 dark:bg-gray-800 rounded-2xl shadow hover:shadow-lg transition"
      >
        <component :is="s.icon" class="w-10 h-10 text-blue-600 dark:text-cyan-400 mb-4" />
        <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-white">{{ s.titulo }}</h3>
        <p class="text-gray-600 dark:text-gray-300">{{ s.descripcion }}</p>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { Bot, BookOpen, Shield, Settings, UserCheck, Database } from "lucide-vue-next";

const servicios = [
  { titulo: "Asistente Virtual Inteligente", descripcion: "Atiende consultas, guía a los usuarios y realiza inscripciones de forma automática.", icon: Bot },
  { titulo: "Gestión de Talleres y Cursos", descripcion: "Creación, edición y organización de talleres con control de cupos y horarios.", icon: BookOpen },
  { titulo: "Inscripciones Automatizadas", descripcion: "Registro rápido de participantes, confirmaciones instantáneas y generación de comprobantes.", icon: Shield },
  { titulo: "Control de Roles y reportes", descripcion: "Asignacion de roles y reportes automáticos para administradores e instructores.", icon: Settings },
  { titulo: "Gestión de Usuarios", descripcion: "Panel administrativo para administrar participantes, instructores y roles.", icon: UserCheck },
  { titulo: "Base de Datos Centralizada", descripcion: "Base de Datos Inteligente Sincronización con sistemas externos y almacenamiento eficiente.", icon: Database },
];
</script>
