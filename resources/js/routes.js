/*jshint esversion: 6 */

import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from '@/js/components/Home';
import Slot from '@/js/components/Slot';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/pages/slots',
      name: 'slots',
      component: Slot
    }
  ]
});

export default router;
