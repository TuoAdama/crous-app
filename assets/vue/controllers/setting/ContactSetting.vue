<script setup>
import {inject, ref} from "vue";
import EditEmailForm from "./EditEmailForm.vue";
import AddNumberSetting from "./AddNumberSetting.vue";
import ResendButton from "./ResendButton.vue";

const props = defineProps(["user", "onUpdate"]);

const message = ref("");
const edit = ref(false);
const isAddNumber = ref(false);
const isEditEmail = ref(false);
const user = ref({...props.user});
const loading = ref(false);
const isSendNumberVerificationLoading = ref(false);

const token = inject("token") ?? "";

const onAddNumber = () => {
  isAddNumber.value = true;
  edit.value = true;
}

const onUpdateEmail = (userUpdated) => {
  user.value = userUpdated;
  props.onUpdate(userUpdated);
  message.value = "Un mail de confirmation vous été envoyé !";
  isEditEmail.value = false;
}

const onResendVerification = async () => {
  loading.value = true;
  const response = await fetch("/setting/resend/email/verification");
  if (response.ok) {
    const data = await response.json();
    message.value = data.message;
    loading.value = false;
  }
}

const onSendNumberVerification = async () => {
  window.location.href = "/setting/verification/resend";
}

</script>

<template>
  <div class="space-y-6">
    <!-- Message d'alerte -->
    <div v-if="message.length" class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
      <div class="flex items-center">
        <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
        <span class="text-yellow-800">{{ message }}</span>
      </div>
    </div>

    <template v-if="!isEditEmail && !isAddNumber">
      <!-- Section Email -->
      <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
              <i class="fas fa-envelope text-blue-600"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Adresse email</h3>
              <p class="text-sm text-gray-700">Gérez votre adresse email principale</p>
            </div>
          </div>
          <button 
            @click="isEditEmail = true" 
            class="btn-modern btn-secondary"
          >
            <i class="fas fa-edit mr-2"></i>
            Modifier
          </button>
        </div>
        
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
          <div class="flex items-center">
            <ResendButton
              :is-loading="loading"
              :is-verified="user.emailIsVerified"
              :on-click="onResendVerification"
              class="text-gray-900 font-medium"
            >
              {{ user.email }}
            </ResendButton>
          </div>
          <div class="flex items-center">
            <span v-if="user.emailIsVerified" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
              <i class="fas fa-check-circle mr-1"></i>
              Vérifié
            </span>
            <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
              <i class="fas fa-exclamation-circle mr-1"></i>
              Non vérifié
            </span>
          </div>
        </div>
      </div>

      <!-- Section Téléphone -->
      <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center">
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
              <i class="fas fa-mobile-alt text-green-600"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Numéro de téléphone</h3>
              <p class="text-sm text-gray-700">Gérez votre numéro de téléphone</p>
            </div>
          </div>
          <button 
            @click="onAddNumber" 
            class="btn-modern btn-secondary"
          >
            <i class="fas fa-edit mr-2"></i>
            {{ user.number ? 'Modifier' : 'Ajouter' }}
          </button>
        </div>
        
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
          <div class="flex items-center">
            <ResendButton
              v-if="user.number"
              :is-loading="isSendNumberVerificationLoading"
              :is-verified="user.numberIsVerified"
              :on-click="onSendNumberVerification"
              class="text-gray-900 font-medium"
            >
              {{ user.number }}
            </ResendButton>
            <span v-else class="text-gray-500 italic">Aucun numéro de téléphone</span>
          </div>
          <div v-if="user.number" class="flex items-center">
            <span v-if="user.numberIsVerified" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
              <i class="fas fa-check-circle mr-1"></i>
              Vérifié
            </span>
            <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
              <i class="fas fa-exclamation-circle mr-1"></i>
              Non vérifié
            </span>
          </div>
        </div>
      </div>
    </template>

    <!-- Formulaires d'édition -->
    <EditEmailForm 
      v-if="isEditEmail && !isAddNumber" 
      :user="user" 
      :on-update="onUpdateEmail"
      :on-cancel="() => isEditEmail = false"
    />
    
    <AddNumberSetting 
      v-if="isAddNumber && !isEditEmail" 
      :on-cancel="() => isAddNumber = false"
    />
  </div>
</template>

<style scoped>
.btn-modern {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  color: white;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 600;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  font-size: 0.875rem;
}

.btn-modern:hover {
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