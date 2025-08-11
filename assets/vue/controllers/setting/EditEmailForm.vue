<script setup>
import {inject, ref} from "vue";
import {ErrorMessage} from "../enum/ErrorMessage";
import {MessageType} from "../enum/MessageType";

  const props = defineProps(["user", "onCancel", "onUpdate"])
  const token = inject("token") ?? "";

  const loading = ref(false)
  const errors = ref({})
  const email = ref();
  const confirmEmail = ref();
  const message = ref({});

  const onSubmit = async (e) => {
    const errorMessage = getFormMessageError();
    message.value.type = null
    message.value.content = null;
    if (errorMessage) {
      message.value.type = MessageType.ERROR;
      message.value.content = errorMessage;
      return;
    }
    loading.value = true;
    const formData =  new FormData(e.target);
    const body = {};
    formData.forEach((value, key) => body[key] = value.toString());

    const response = await  fetch("/setting/edit/email", {
      method: "POST",
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(body),
    })
    const result = await response.json();
    if (response.status === 200) {
      const {user} = result;
      props.onUpdate(user);
    } else if (response.status ===  422) {
      if (result.errors) {
        errors.value = result.errors;
      }
      if (result.detail) {
        message.value.type = MessageType.SUCCESS;
        message.value.content = result.detail;
      }
    }

    loading.value = false
  }

  const getFormMessageError = () => {
    if (props.user.email === email.value) {
      return ErrorMessage.DIFFERENT_EMAIL_ERROR_MESSAGE;
    }
    if (email.value !== confirmEmail.value) {
      return ErrorMessage.CONFIRM_EMAIL_NOT_IDENTICAL_ERROR_MESSAGE;
    }
    return null;
  }

</script>

<template>
  <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
    <div class="flex items-center mb-6">
      <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
        <i class="fas fa-envelope text-blue-600"></i>
      </div>
      <div>
        <h3 class="text-lg font-semibold text-gray-900">Modifier l'adresse email</h3>
        <p class="text-sm text-gray-700">Entrez votre nouvelle adresse email</p>
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

      <input type="hidden" name="token" :value="token">

      <!-- Nouvelle adresse email -->
      <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-900">
          Nouvelle adresse email
        </label>
        <input 
          v-model="email" 
          name="email" 
          type="email" 
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900"
          placeholder="nouvelle.email@exemple.com"
        >
        <div v-if="'email' in errors" class="text-red-600 text-sm flex items-center">
          <i class="fas fa-exclamation-circle mr-2"></i>
          {{ errors.email }}
        </div>
      </div>

      <!-- Confirmation email -->
      <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-900">
          Confirmation de l'adresse email
        </label>
        <input 
          v-model="confirmEmail" 
          name="confirmEmail" 
          type="email" 
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900"
          placeholder="nouvelle.email@exemple.com"
        >
      </div>

      <!-- Mot de passe -->
      <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-900">
          Mot de passe actuel
        </label>
        <input 
          name="password" 
          type="password" 
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900"
          placeholder="Votre mot de passe actuel"
        >
        <div v-if="'password' in errors" class="text-red-600 text-sm flex items-center">
          <i class="fas fa-exclamation-circle mr-2"></i>
          {{ errors.password }}
        </div>
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
          <i v-else class="fas fa-save mr-2"></i>
          {{ loading ? 'Enregistrement...' : 'Enregistrer' }}
        </button>
        
        <button 
          :disabled="loading" 
          type="button"
          @click="props.onCancel" 
          class="btn-modern btn-secondary flex-1 sm:flex-none justify-center"
        >
          <i class="fas fa-times mr-2"></i>
          Annuler
        </button>
      </div>
    </form>
  </div>
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