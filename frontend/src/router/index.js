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
    meta: { auth: true, roles: ['student'] },
    component: () => import('../layouts/DashboardLayout.vue'),
    children: [
      { path: 'dashboard', name: 'student-dashboard', component: () => import('../views/student/StudentDashboard.vue') },
      { path: 'applications', name: 'student-applications', component: () => import('../views/student/ApplicationTracking.vue') },
      { path: 'apply/:internshipId', name: 'student-apply', component: () => import('../views/student/ApplyInternship.vue'), props: true },
      { path: 'report', name: 'student-report', component: () => import('../views/student/UploadReport.vue') },
    ],
  },
  {
    path: '/company',
    meta: { auth: true, roles: ['company'] },
    component: () => import('../layouts/DashboardLayout.vue'),
    children: [
      { path: 'dashboard', name: 'company-dashboard', component: () => import('../views/company/CompanyDashboard.vue') },
      { path: 'internships/new', name: 'company-post', component: () => import('../views/company/PostInternship.vue') },
      { path: 'applications', name: 'company-applications', component: () => import('../views/company/ManageApplications.vue') },
    ],
  },
  {
    path: '/admin',
    meta: { auth: true, roles: ['admin'] },
    component: () => import('../layouts/DashboardLayout.vue'),
    children: [
      { path: 'dashboard', name: 'admin-dashboard', component: () => import('../views/admin/AdminDashboard.vue') },
    ],
  },
  {
    path: '/supervisor',
    meta: { auth: true, roles: ['supervisor'] },
    component: () => import('../layouts/DashboardLayout.vue'),
    children: [
      { path: 'dashboard', name: 'supervisor-dashboard', component: () => import('../views/supervisor/SupervisorDashboard.vue') },
      { path: 'evaluation', name: 'supervisor-evaluation', component: () => import('../views/supervisor/EvaluationPage.vue') },
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
