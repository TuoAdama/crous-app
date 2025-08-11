<script setup>
  import { provide, ref} from "vue";
  import ContactSetting from "./ContactSetting.vue";
  import BaseSetting from "./BaseSetting.vue";

  const props = defineProps(["user", "token"])

  provide('token', props.token);

  const user = ref({...props.user});
  const edit = ref(false);

  const onUpdateUser = (updatedUser) => {
    user.value = updatedUser;
  }

  const onEdit = () => {
    edit.value = !edit.value;
  }
</script>

<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Breadcrumb -->
      <nav class="mb-8" aria-label="breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
          <li>
            <a href="/" class="hover:text-blue-600 transition-colors duration-200 flex items-center">
              <i class="fas fa-home mr-2"></i>
              Accueil
            </a>
          </li>
          <li class="flex items-center">
            <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
            <span class="text-gray-800 font-medium">Mon Compte</span>
          </li>
        </ol>
      </nav>

      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Profil Card -->
        <div class="lg:col-span-1">
          <div class="card-modern p-6 text-center">
            <div class="relative mb-6">
              <div class="w-32 h-32 mx-auto relative">
                <img 
                  src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" 
                  alt="Photo de profil" 
                  class="w-full h-full rounded-full object-cover border-4 border-white shadow-lg"
                >
                <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-white flex items-center justify-center">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
              </div>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ user.username }}</h2>
            <p class="text-gray-700 mb-4">{{ user.email }}</p>
            
            <div class="space-y-3">
              <div class="flex items-center justify-center text-sm text-gray-600">
                <i class="fas fa-envelope mr-2 text-green-500"></i>
                <span>Email vérifié</span>
              </div>
              <div class="flex items-center justify-center text-sm text-gray-600">
                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                <span>Membre depuis 2024</span>
              </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
              <button 
                @click="onEdit"
                class="btn-modern w-full"
              >
                <i class="fas fa-edit mr-2"></i>
                Modifier le profil
              </button>
            </div>
          </div>
        </div>

        <!-- Settings Content -->
        <div class="lg:col-span-3 space-y-6">
          <!-- Base Settings -->
          <div class="card-modern p-6">
            <div class="flex items-center justify-between mb-6">
              <div>
                <h3 class="text-xl font-bold text-gray-900">Informations de base</h3>
                <p class="text-gray-700 mt-1">Gérez vos informations personnelles</p>
              </div>
              <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-blue-600"></i>
              </div>
            </div>
            <BaseSetting :user="user" :on-update="onUpdateUser" />
          </div>

          <!-- Contact Settings -->
          <div class="card-modern p-6">
            <div class="flex items-center justify-between mb-6">
              <div>
                <h3 class="text-xl font-bold text-gray-900">Informations de contact</h3>
                <p class="text-gray-700 mt-1">Gérez vos coordonnées et préférences</p>
              </div>
              <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-phone text-green-600"></i>
              </div>
            </div>
            <ContactSetting :user="user" :on-update="onUpdateUser" />
          </div>

          <!-- Security Section -->
          <div class="card-modern p-6">
            <div class="flex items-center justify-between mb-6">
              <div>
                <h3 class="text-xl font-bold text-gray-900">Sécurité</h3>
                <p class="text-gray-700 mt-1">Protégez votre compte</p>
              </div>
              <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-shield-alt text-red-600"></i>
              </div>
            </div>
            
            <div class="space-y-4">
              <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex items-center">
                  <i class="fas fa-lock text-gray-700 mr-3"></i>
                  <div>
                    <h4 class="font-medium text-gray-900">Mot de passe</h4>
                    <p class="text-sm text-gray-600">Dernière modification il y a 30 jours</p>
                  </div>
                </div>
                <button class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors duration-200">
                  Modifier
                </button>
              </div>
              
              <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex items-center">
                  <i class="fas fa-mobile-alt text-gray-700 mr-3"></i>
                  <div>
                    <h4 class="font-medium text-gray-900">Authentification à deux facteurs</h4>
                    <p class="text-sm text-gray-600">Ajoutez une couche de sécurité supplémentaire</p>
                  </div>
                </div>
                <button class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors duration-200">
                  Activer
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.card-modern {
  background: white;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  border: 1px solid #e5e7eb;
  transition: all 0.3s ease;
}

.card-modern:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.btn-modern {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  color: white;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-modern:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
}
</style>