require("babel-polyfill");

import modal from './components/modal.vue';
import VideoModal from './components/VideoModal.vue';
import GoogleMap from './components/GoogleMap.vue';
import GoogleMapPin from './components/GoogleMapPin.vue';
import {VueMasonryPlugin} from 'vue-masonry';
import InstaGallery from './components/InstaGallery';
import SocialSharingIcons from './components/SocialSharingIcons';
import DonationForm from './components/DonationForm';
import ContactForm from './components/ContactForm';
import Vue from 'vue/dist/vue';

window.Vue = Vue;

Vue.use(VueMasonryPlugin)

let app = new Vue({

    el: '#app',

    components: {
        'modal': modal,
        'video-modal' : VideoModal,
        'google-map': GoogleMap,
        'pin': GoogleMapPin,
        'insta-gallery': InstaGallery,
        'social-sharing-icons': SocialSharingIcons,
        'donations-form': DonationForm,
        'contact-form': ContactForm
    },

    data: {
        isOpen: false,
        isScrolling: false,
        modalOpen: false,
        modalContent: '',
        scrollPosition: 0,
        footerStuck: false,
        clientHeight: 0,
        windowHeight: 0,
        windowWidth: 0,
        menuItems: []
    },

    methods: {

        toggleMenu(){
            this.isOpen = !this.isOpen;
        },

        handleScroll(){
            this.scrollPosition = window.scrollY;
            this.isScrolling = this.scrollPosition > 40;
        },

        handleMobileSubMenu(){
            this.menuItems.forEach(menuItem => {
                let menuLink = menuItem.querySelector('.mobile-expand');
                if(menuLink != null) {
                    menuLink.addEventListener('click', function (e) {
                        e.preventDefault();
                        let menu = menuItem.querySelector('.navbar-dropdown');
                        if (menu.classList.contains('is-open')) {
                            menu.classList.remove('is-open');
                        } else {
                            menu.classList.add('is-open');
                        }
                    });
                }
            });
        }

    },

    mounted: function() {
        this.footerStuck = window.innerHeight > this.$root.$el.clientHeight;
        this.clientHeight = this.$root.$el.clientHeight;
        this.windowHeight = window.innerHeight;
        this.windowWidth = window.innerWidth;
        this.handleScroll();
        this.menuItems = this.$el.querySelectorAll('#MobileNavMenu .navbar-item');
        this.handleMobileSubMenu();
    },

    created: function () {
        window.addEventListener('scroll', this.handleScroll);
    },

    destroyed: function () {
        window.removeEventListener('scroll', this.handleScroll);
    }

});

