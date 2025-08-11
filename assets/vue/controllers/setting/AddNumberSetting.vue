<script setup>
import {inject, ref} from "vue";
import {MessageType} from "../enum/MessageType";
import NumerVerificationForm from "./NumerVerificationForm.vue";

  const props = defineProps(["onCancel"])
  const token = inject("token") ?? "";
  const loading = ref(false);
  const showVerificationForm = ref(false);
  const message = ref({});

  const number = ref("");
  const onSubmit = async (e) => {
    loading.value = true;
    const response = await fetch("/setting/resend/number/verification", {
      method: "POST",
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({token, number: number.value}),
    })
    const data = await response.json();
    let responseType = null;

    let responseMessage = data.message;
    if (response.status === 200) {
        showVerificationForm.value = true;
        responseType = MessageType.SUCCESS;
    } else if (response.status === 422) {
      responseType = MessageType.ERROR;
    } else if (response.status === 500) {
      responseType = MessageType.ERROR;
      responseMessage = "Une erreur est survenue, veuillez ressayer plus tard";
    }
    message.value.type = responseType;
    message.value.content = responseMessage;
    loading.value = false;
  }

</script>

<template>
  <div v-if="!showVerificationForm">
    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
      <div class="flex items-center mb-6">
        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
          <i class="fas fa-mobile-alt text-green-600"></i>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-900">Ajouter un numéro de téléphone</h3>
          <p class="text-sm text-gray-700">Entrez votre numéro de téléphone français</p>
        </div>
      </div>

      <form @submit.prevent="onSubmit" class="space-y-6">
        <!-- Message d'alerte -->
        <div v-if="message.type" :class="`p-4 rounded-lg border ${
          message.type === 'success' 
            ? 'bg-green-50 border-green-200 text-green-800' 
            : 'bg-red-50 border-red-200 text-red-800'
        }`">
          <div class="flex items-center">
            <i :class="`fas ${message.type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-3`"></i>
            {{ message.content }}
          </div>
        </div>

        <input type="hidden" name="token" :value="token" />

        <!-- Numéro de téléphone -->
        <div class="space-y-2">
          <label class="block text-sm font-medium text-gray-900">
            Numéro de téléphone
          </label>
          <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 bg-white">
            <div class="px-3 py-2 bg-gray-50 text-gray-700 font-medium border-r border-gray-300 rounded-l-lg">
              +33
            </div>
            <input 
              name="number" 
              v-model="number" 
              type="text" 
              pattern="0[1-9]{1}[0-9]{8}" 
              required
              class="flex-1 px-3 py-2 border-0 focus:outline-none focus:ring-0 bg-transparent text-gray-900"
              placeholder="6 12 34 56 78"
            >
          </div>
          <p class="text-xs text-gray-500">
            Format attendu : 06 12 34 56 78 (sans espaces)
          </p>
        </div>

        <!-- Boutons d'action -->
        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
          <button 
            :disabled="loading" 
            type="submit" 
            class="btn-modern flex-1 sm:flex-none justify-center"
            :class="{ 'opacity-50 cursor-not-allowed': loading }"
          >
            <i v-if="loading" class="fas fa-spinner fa-spin mr-2"></i>
            <i v-else class="fas fa-paper-plane mr-2"></i>
            {{ loading ? 'Envoi...' : 'Envoyer le code' }}
          </button>
          
          <button 
            type="button"
            @click="props.onCancel" 
            class="btn-modern btn-secondary flex-1 sm:flex-none justify-center"
            :disabled="loading"
          >
            <i class="fas fa-times mr-2"></i>
            Annuler
          </button>
        </div>
      </form>
    </div>
  </div>
  
  <NumerVerificationForm v-else>
    <div v-if="message.type" :class="`p-4 rounded-lg border ${
      message.type === 'success' 
        ? 'bg-green-50 border-green-200 text-green-800' 
        : 'bg-red-50 border-red-200 text-red-800'
    }`">
      <div class="flex items-center">
        <i :class="`fas ${message.type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-3`"></i>
        {{ message.content }}
      </div>
    </div>
  </NumerVerificationForm>
</template>

<style scoped>
.btn-modern {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  color: white;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
  text-decoration: none;
  display: inline-flex;
  align-items: center;
}

.btn-modern:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
  color: white;
  text-decoration: none;
}

.btn-modern.btn-secondary {
  background: white;
  color: #374151;
  border: 2px solid #e5e7eb;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.btn-modern.btn-secondary:hover {
  background: #f9fafb;
  color: #374151;
  border-color: #d1d5db;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}
</style>