import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const routes = [
  { path: '/', redirect: '/internships' },
  { path: '/login', name: 'login', component: () => import('../views/Login.vue'), meta: { guest: true } },
  { path: '/register', name: 'register', component: () => import('../views/Register.vue'), meta: { guest: true } },
  { path: '/internships', name: 'internships', component: () => import('../views/InternshipList.vue') },
  { path: '/internships/:id', name: 'internship-detail', component: () => import('../views/InternshipDetails.vue'), props: true },
  {
    path: '/student',
    meta: { auth: true, roles: ['student'], dashboard: true },
    component: () => import('../layouts/DashboardLayout.vue'),
    children: [
      {
        path: 'dashboard',
        name: 'student-dashboard',
        meta: { title: 'Tableau de bord' },
        component: () => import('../views/student/StudentDashboard.vue'),
      },
      {
        path: 'applications',
        name: 'student-applications',
        meta: { title: 'Mes candidatures' },
        component: () => import('../views/student/ApplicationTracking.vue'),
      },
      {
        path: 'apply/:internshipId',
        name: 'student-apply',
        meta: { title: 'Postuler' },
        component: () => import('../views/student/ApplyInternship.vue'),
        props: true,
      },
      {
        path: 'report',
        name: 'student-report',
        meta: { title: 'Rapport de stage' },
        component: () => import('../views/student/UploadReport.vue'),
      },
      {
        path: 'profile',
        name: 'student-profile',
        meta: { title: 'Mon profil' },
        component: () => import('../views/student/StudentProfile.vue'),
      },
    ],
  },
  {
    path: '/company',
    meta: { auth: true, roles: ['company'], dashboard: true },
    component: () => import('../layouts/DashboardLayout.vue'),
    children: [
      {
        path: 'dashboard',
        name: 'company-dashboard',
        meta: { title: 'Vue d’ensemble' },
        component: () => import('../views/company/CompanyDashboard.vue'),
      },
      {
        path: 'internships/new',
        name: 'company-post',
        meta: { title: 'Nouvelle offre' },
        component: () => import('../views/company/PostInternship.vue'),
      },
      {
        path: 'internships',
        name: 'company-internships',
        meta: { title: 'Gérer les stages' },
        component: () => import('../views/company/CompanyInternships.vue'),
      },
      {
        path: 'internships/:id/edit',
        name: 'company-internship-edit',
        meta: { title: 'Modifier une offre' },
        component: () => import('../views/company/CompanyInternshipEdit.vue'),
        props: true,
      },
      {
        path: 'applications',
        name: 'company-applications',
        meta: { title: 'Candidatures' },
        component: () => import('../views/company/ManageApplications.vue'),
      },
    ],
  },
  {
    path: '/admin',
    meta: { auth: true, roles: ['admin'], dashboard: true },
    component: () => import('../layouts/DashboardLayout.vue'),
    children: [
      {
        path: 'dashboard',
        name: 'admin-dashboard',
        meta: { title: 'Analytics' },
        component: () => import('../views/admin/AdminDashboard.vue'),
      },
      {
        path: 'students',
        name: 'admin-students',
        meta: { title: 'Étudiants' },
        component: () => import('../views/admin/AdminStudents.vue'),
      },
      {
        path: 'companies',
        name: 'admin-companies',
        meta: { title: 'Entreprises' },
        component: () => import('../views/admin/AdminCompanies.vue'),
      },
      {
        path: 'applications',
        name: 'admin-applications',
        meta: { title: 'Candidatures' },
        component: () => import('../views/admin/AdminApplications.vue'),
      },
      {
        path: 'applications/:id',
        name: 'admin-application-detail',
        meta: { title: 'Détail candidature' },
        component: () => import('../views/admin/AdminApplicationDetail.vue'),
        props: true,
      },
    ],
  },
  {
    path: '/supervisor',
    meta: { auth: true, roles: ['supervisor'], dashboard: true },
    component: () => import('../layouts/DashboardLayout.vue'),
    children: [
      {
        path: 'dashboard',
        name: 'supervisor-dashboard',
        meta: { title: 'Supervision' },
        component: () => import('../views/supervisor/SupervisorDashboard.vue'),
      },
      {
        path: 'evaluation',
        name: 'supervisor-evaluation',
        meta: { title: 'Nouvelle évaluation' },
        component: () => import('../views/supervisor/EvaluationPage.vue'),
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(async (to) => {
  const auth = useAuthStore()
  if (to.meta.auth && !auth.token) {
    return { name: 'login', query: { redirect: to.fullPath } }
  }
  if (to.meta.roles?.length && auth.token) {
    if (!auth.user) {
      try {
        await auth.fetchMe()
      } catch {
        return { name: 'login' }
      }
    }
    if (!to.meta.roles.includes(auth.user.role)) {
      return { path: '/' }
    }
  }
  if (to.meta.guest && auth.token) {
    return { path: '/internships' }
  }
  return true
})

export default router
