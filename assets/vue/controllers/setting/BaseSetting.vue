<script setup>
  import {computed, inject, ref} from "vue";
  import {MessageType} from "../enum/MessageType";

  const props = defineProps(["user","onUpdate"]);
  const edit = ref(false);
  const user = ref({...props.user})
  const oldUser = ref({...props.user});

  const loading = ref(false);
  const message = ref(null);

  const token = inject('token') ?? "";

  const onEdit = () => {
    if (!edit.value) {
      edit.value = true;
      return;
    }
    edit.value = false;
  }

  const onCancel = () => {
    edit.value = false;
    user.value = {...props.user};
  }

  const isBaseInformationChange =  computed(() => {
    const currentUser = user.value;
    const oldUserValue = oldUser.value;

    return oldUserValue.username !== currentUser.username
        || oldUserValue.email !== currentUser.email
        || oldUserValue.username !== currentUser.username
        || oldUserValue.username !== currentUser.username
        || oldUserValue.notifyByEmail !== currentUser.notifyByEmail
        || oldUserValue.notifyByNumber !== currentUser.notifyByNumber;
  });

  const onSubmit = async () => {
    loading.value = true;
    const {username, notifyByEmail, notifyByNumber} = user.value;
    const body = {username, notifyByEmail, notifyByNumber, token};
    const response = await fetch("/setting/edit/base-information", {
      method: "POST",
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(body),
    });

    const data = await response.json();

    if (response.status === 200) {
      const {user: userUpdated, message: responseMessage} = data;
      user.value = userUpdated;
      oldUser.value = {...userUpdated};
      props.onUpdate({...userUpdated});
      message.value = {
        type: MessageType.SUCCESS,
        content: responseMessage,
      }
    } else {

      message.value = {
        type: MessageType.ERROR,
        content: 'Form is invalid',
      }
    }

    loading.value = false;
  }

</script>

<template>
  <form @submit.prevent="onSubmit" class="space-y-6">
    <!-- Message d'alerte -->
    <div v-if="message" :class="`p-4 rounded-lg border ${
      message.type === 'success' 
        ? 'bg-green-50 border-green-200 text-green-800' 
        : 'bg-red-50 border-red-200 text-red-800'
    }`">
      <div class="flex items-center">
        <i :class="`fas ${message.type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-3`"></i>
        {{ message.content }}
      </div>
    </div>

    <!-- Informations de base -->
    <div class="space-y-4">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
        <div class="flex items-center mb-3 sm:mb-0">
          <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
            <i class="fas fa-user text-blue-600"></i>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-900 mb-1">Nom d'utilisateur</label>
            <input 
              type="hidden" 
              name="_token" 
              :value="token" 
            />
            <input 
              name="username" 
              type="text" 
              v-model="user.username"
              class="w-full sm:w-64 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900"
              placeholder="Votre nom d'utilisateur"
            >
          </div>
        </div>
      </div>
    </div>

    <!-- Section Notifications -->
    <div class="border-t border-gray-200 pt-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
        <i class="fas fa-bell text-blue-600 mr-2"></i>
        Préférences de notification
      </h3>
      
      <div class="space-y-4">
        <!-- Notification par email -->
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
          <div class="flex items-center">
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
              <i class="fas fa-envelope text-green-600"></i>
            </div>
            <div>
              <h4 class="font-medium text-gray-900">Notifications par email</h4>
              <p class="text-sm text-gray-600">Recevoir les alertes par email</p>
            </div>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input 
              name="notifyByEmail" 
              type="checkbox" 
              v-model="user.notifyByEmail"
              class="sr-only peer"
            >
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
          </label>
        </div>

        <!-- Notification par téléphone -->
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
          <div class="flex items-center">
            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
              <i class="fas fa-mobile-alt text-purple-600"></i>
            </div>
            <div>
              <h4 class="font-medium text-gray-900">Notifications par SMS</h4>
              <p class="text-sm text-gray-600">Recevoir les alertes par SMS</p>
            </div>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input 
              name="notifyByNumber" 
              type="checkbox" 
              v-model="user.notifyByNumber"
              class="sr-only peer"
            >
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
          </label>
        </div>
      </div>
    </div>

    <!-- Boutons d'action -->
    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
      <button 
        :disabled="!isBaseInformationChange || loading" 
        type="submit" 
        class="btn-modern flex-1 sm:flex-none justify-center"
        :class="{ 'opacity-50 cursor-not-allowed': !isBaseInformationChange || loading }"
      >
        <i v-if="loading" class="fas fa-spinner fa-spin mr-2"></i>
        <i v-else class="fas fa-save mr-2"></i>
        {{ loading ? 'Enregistrement...' : 'Enregistrer' }}
      </button>
      
      <button 
        v-if="isBaseInformationChange && !loading"
        type="button" 
        @click="onCancel" 
        class="btn-modern btn-secondary flex-1 sm:flex-none justify-center"
      >
        <i class="fas fa-times mr-2"></i>
        Annuler
      </button>
    </div>
  </form>
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