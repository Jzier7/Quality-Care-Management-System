import { admin } from 'src/middleware/admin';
import { worker } from 'src/middleware/worker';
import { patient } from 'src/middleware/patient';

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

  {
    path: '/admin',
    component: () => import('layouts/AdminLayout.vue'),
    meta: { middlewares: [admin] },
    children: [
      { path: 'dashboard', component: () => import('pages/Admin/AdminDashboard.vue') },
      { path: 'healthcare-workers', component: () => import('pages/Admin/HealthCareWorkers.vue') },
      { path: 'medical-records', component: () => import('src/pages/Admin/PatientInformations.vue') },
      { path: 'positions', component: () => import('src/pages/Admin/WorkerPositions.vue') },
      { path: 'information-board', component: () => import('pages/Admin/InformationBoard.vue') },
      { path: 'emergency-contact', component: () => import('pages/Admin/EmergencyContact.vue') },
    ]
  },

  {
    path: '/healthcare-worker',
    component: () => import('layouts/HealthCareWorkerLayout.vue'),
    meta: { middlewares: [worker] },
    children: [
      { path: 'medical-records', component: () => import('src/pages/Admin/PatientInformations.vue') },
      { path: 'schedule-checkup', component: () => import('pages/HealthCareWorker/ScheduleCheckUp.vue') },
      { path: 'information-board', component: () => import('pages/HealthCareWorker/InformationBoard.vue') },
      { path: 'user-profile', component: () => import('pages/HealthCareWorker/UserProfile.vue') },
    ]
  },

  {
    path: '/patient',
    component: () => import('layouts/PatientLayout.vue'),
    meta: { middlewares: [patient] },
    children: [
      { path: 'medical-records', component: () => import('pages/Patient/MedicalRecords.vue') },
      { path: 'healthcare-workers', component: () => import('pages/Patient/HealthCareWorkers.vue') },
      { path: 'schedule-checkup', component: () => import('pages/Patient/ScheduleCheckUp.vue') },
      { path: 'information-board', component: () => import('pages/HealthCareWorker/InformationBoard.vue') },
      { path: 'emergency-contact', component: () => import('pages/Patient/EmergencyContact.vue') },
      { path: 'user-profile', component: () => import('pages/Patient/UserProfile.vue') },
    ]
  },

  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes

