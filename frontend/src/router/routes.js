const routes = [
  {
    path: '/',
    component: () => import('pages/Auth/SignIn.vue'),
  },

  {
    path: '/signup',
    component: () => import('pages/Auth/SignUp.vue'),
  },

  {
    path: '/forgot-password',
    component: () => import('pages/Auth/ForgotPassword.vue'),
  },

  // Admin Routes
  {
    path: '/admin',
    component: () => import('layouts/AdminLayout.vue'),
    children: [
      { path: 'healthcare-workers', component: () => import('pages/Admin/HealthCareWorkers.vue') },
      { path: 'information-board', component: () => import('pages/Admin/InformationBoard.vue') },
      { path: 'emergency-contact', component: () => import('pages/Admin/EmergencyContact.vue') },
    ]
  },

  {
    path: '/healthcare-worker',
    component: () => import('layouts/HealthCareWorkerLayout.vue'),
    children: [
      { path: 'medical-records', component: () => import('pages/HealthCareWorker/MedicalRecords.vue') },
      { path: 'schedule-checkup', component: () => import('pages/HealthCareWorker/ScheduleCheckUp.vue') },
      { path: 'information-board', component: () => import('pages/HealthCareWorker/InformationBoard.vue') },
    ]
  },

  {
    path: '/patient',
    component: () => import('layouts/PatientLayout.vue'),
    children: [
      { path: 'medical-records', component: () => import('pages/Patient/MedicalRecords.vue') },
      { path: 'healthcare-workers', component: () => import('pages/Patient/HealthCareWorkers.vue') },
      { path: 'schedule-checkup', component: () => import('pages/Patient/ScheduleCheckUp.vue') },
      { path: 'information-board', component: () => import('pages/Patient/InformationBoard.vue') },
      { path: 'emergency-contact', component: () => import('pages/Patient/EmergencyContact.vue') },
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes

