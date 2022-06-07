import Home from './layout/Home';
import JobsLayout from './layout/JobsLayout.vue';
import Login from './layout/Login.vue';
import JoinNow from './layout/JoinNow.vue';
import CompaniesLayout from './layout/CompaniesLayout.vue';
import JobSearch from './layout/JobSearch.vue';
import CompanyJobsLayout from './layout/CompanyJobsLayout.vue';
export const routes = [{
        path: '/',
        name: 'home',
        component: Home
    }, {
        path: '/jobs',
        name: 'jobs',
        component: JobsLayout
    }, {
        path: '/companies',
        name: 'companies',
        component: CompaniesLayout
    }, {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/join-now',
        name: 'join_now',
        component: JoinNow
    },
    {
        path: '/job-search/:job',
        name: 'job_search',
        component: JobSearch,
        props: true,
    },
    {
        path: '/company-jobs/:id',
        name: 'company_jobs',
        component: CompanyJobsLayout,
        props: true
    }

];