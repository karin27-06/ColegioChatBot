<template> 
  <section 
    id="contacto" 
    class="flex min-h-screen flex-col items-center justify-center 
           bg-white dark:bg-gray-900 
           px-6 py-16 transition-colors duration-500"
  >
    <!-- Título -->
    <div class="mb-12 text-center">
      <h2 class="mb-4 text-3xl font-bold text-neutral-900 dark:text-white">
        Contáctanos
      </h2>
      <p class="mx-auto mt-4 max-w-2xl text-lg text-neutral-600 dark:text-gray-300">
        ¿Tienes dudas sobre el sistema de Gestion e inscripciones de alumnas?<br />
        Escríbenos y nuestro equipo técnico te brindará asistencia personalizada.
      </p>
    </div>

    <!-- Opciones de contacto -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-2 max-w-4xl w-full">
      <div
        v-for="contacto in contactos"
        :key="contacto.title"
        class="flex items-start gap-4 p-6 
               bg-white dark:bg-gray-800 
               rounded-2xl shadow-md hover:shadow-lg 
               border border-gray-200 dark:border-gray-700
               transition"
      >
        <!-- Icono -->
        <div class="flex h-12 w-12 items-center justify-center 
                    rounded-full bg-blue-100 dark:bg-gray-700">
          <component :is="contacto.icon" class="h-6 w-6 text-blue-600 dark:text-blue-400" />
        </div>

        <!-- Texto -->
        <div>
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
            {{ contacto.title }}
          </h3>
          <p class="mt-1 text-gray-600 dark:text-gray-300" v-html="contacto.info"></p>
        </div>
      </div>
    </div>

    <!-- Botón que abre el modal -->
    <button 
      @click="mostrarModal = true"
      class="mt-12 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 shadow">
      Enviar mensaje
    </button>

    <!-- Modal Simple -->
    <div v-if="mostrarModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Formulario de contacto</h3>
          <button 
            @click="mostrarModal = false"
            class="text-gray-500 hover:text-gray-700 text-2xl"
          >
            &times;
          </button>
        </div>

        <!-- Formulario -->
        <form @submit.prevent="enviarFormulario" class="grid gap-6">
          <div>
            <label class="block text-gray-700 dark:text-gray-200 font-medium mb-2">Nombre</label>
            <input 
              v-model="form.first_name" 
              type="text" 
              class="w-full rounded-lg border px-4 py-2" required 
              placeholder="Ingresa tu nombre completo"
            />
          </div>

          <div>
            <label class="block text-gray-700 dark:text-gray-200 font-medium mb-2">Correo electrónico</label>
            <input v-model="form.email" type="email" class="w-full rounded-lg border px-4 py-2" required placeholder="correo@ejemplo.com" />
          </div>

          <div>
            <label class="block text-gray-700 dark:text-gray-200 font-medium mb-2">Teléfono</label>
            <input v-model="form.phone" type="text" maxlength="9" class="w-full rounded-lg border px-4 py-2" required placeholder="999999999" />
          </div>

          <div>
            <label class="block text-gray-700 dark:text-gray-200 font-medium mb-2">Mensaje</label>
            <textarea v-model="form.subject" rows="4" class="w-full rounded-lg border px-4 py-2" required placeholder="Escríbenos tus consultas técnicas o solicitudes..."></textarea>
          </div>

          <div class="flex gap-4">
            <button type="button" @click="limpiarFormulario" 
              class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 px-6 py-2 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500">
              Borrar
            </button>
            <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
              Enviar
            </button>
          </div>

          <!-- Mensaje -->
          <p v-if="mensaje.text" 
             :class="mensaje.tipo === 'success' 
                      ? 'text-green-600 dark:text-green-400 mt-4 text-sm text-center' 
                      : 'text-red-600 dark:text-red-400 mt-4 text-sm text-center'">
            {{ mensaje.text }}
          </p>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import axios from 'axios'
import { MapPin, Phone, Mail, Cpu } from 'lucide-vue-next'

const mostrarModal = ref(false)

const contactos = [
  { title: 'Oficina principal', icon: MapPin, info: 'Av. José de Lama N° 2302 - Urbanización Santa Rosa - Piura-Sullana' },
  { title: 'Soporte técnico', icon: Phone, info: `<a href="https://wa.me/51930992236" target="_blank">+51 930 992 236</a> - <a href="https://wa.me/51957684541" target="_blank">+51 957 684 541</a>` },
  { title: 'Correo institucional', icon: Mail, info: `<a href="mailto:aamussem@ucvvirtual.edu.pe">aamussem@ucvvirtual.edu.pe</a> - <a href="mailto:aaronleandro.musemilla2017@gmail.com">aaronleandro.musemilla2017@gmail.com</a>` },
  { title: 'Área de desarrollo', icon: Cpu, info: 'Equipo especializado en recoleccion de datos, gestion de talleres y eficacia tecnologica.' },
]

const mensaje = reactive({
  text: '',
  tipo: ''
})

const form = reactive({
  first_name: '',
  email: '',
  phone: '',
  subject: ''
})

const limpiarFormulario = () => {
  form.first_name = ''
  form.email = ''
  form.phone = ''
  form.subject = ''
}

const enviarFormulario = async () => {
  try {
    // Construir el mensaje para WhatsApp
    const mensajeWhatsApp = `¡Hola! Me contacto desde el sistema de Gestión e Inscripciones INIF 48

📋 *Información del contacto:*
👤 *Nombre:* ${form.first_name}
📧 *Email:* ${form.email}
📱 *Teléfono:* ${form.phone}

💬 *Mensaje:*
${form.subject}

¡Gracias por contactarnos! 😊`;

    // PASO 1: Enviar datos al backend para guardar en BD
    const response = await axios.post('/contacto-whatsapp', {
      first_name: form.first_name,
      email: form.email,
      phone: form.phone,
      subject: form.subject,
      whatsapp_message: mensajeWhatsApp
    });

    if (response.data.success) {
      // PASO 2: Si se guardó exitosamente, abrir WhatsApp
      const mensajeCodificado = encodeURIComponent(mensajeWhatsApp);
      const urlWhatsApp = `https://wa.me/51930992236?text=${mensajeCodificado}`;
      window.open(urlWhatsApp, '_blank');
      
      // Mostrar mensaje de éxito
      mensaje.text = '¡Perfecto! Tu mensaje fue guardado y se abrió WhatsApp. Solo presiona "Enviar" ahí.';
      mensaje.tipo = 'success'
      limpiarFormulario()
    } else {
      throw new Error(response.data.message || 'Error desconocido');
    }
    
  } catch (error) {
    console.error('Error:', error)
    mensaje.text = 'Error al guardar tu mensaje. Por favor, intenta nuevamente.'
    mensaje.tipo = 'error'
  }

  setTimeout(() => {
    mensaje.text = ''
    mensaje.tipo = ''
  }, 6000)
}
</script>
