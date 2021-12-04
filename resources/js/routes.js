export default [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/monitor', component: require('./components/monitor/Monitor.vue').default },
    {path: '/log-activity', component: require('./components/log/activities.vue').default},
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '*', component: require('./components/NotFound.vue').default }
];
