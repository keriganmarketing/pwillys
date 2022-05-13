<template>
  <div class="section">
    <div class="columns is-full-width is-multiline">
      
      <div class="is-narrow is-flex">
        <div class="column has-text-right pad-5 is-half-mobile is-inline-block">
          <button @click="setLanguage('english')" class="button is-rounded" :class="{ 'is-secondary': language == 'english', 'is-primary': language == 'spanish' }" >
            <span v-if="language=='english'" >English</span>
            <span v-else>Inglés</span>
          </button>
        </div>
        <div class="column has-text-left pad-5 is-half-mobile is-inline-block">
          <button @click="setLanguage('spanish')" class="button is-rounded" :class="{ 'is-secondary': language == 'spanish', 'is-primary': language == 'english' }" >
            <span v-if="language=='english'" >Spanish</span>
            <span v-else>Español</span>
          </button>
        </div>
      </div>

      <div v-if="language == 'english'" class="column is-auto columns items-justified-right" >
        <div class="column is-narrow" style="text-align: center;">
          <a 
            v-if="englishMenuPdf != {}"
            class="button is-rounded is-primary is-inline-block" 
            :href="englishMenuPdf.url"
            target="_blank"
          >
              PDF Menu
          </a>
        </div>
        <div class="column is-narrow" style="text-align: center;">
          <a 
            v-if="englishAllergenPdf != {}"
            class="button is-rounded is-primary is-inline-block" 
            :href="englishAllergenPdf.url"
            target="_blank"
          >
              Allergen Guide
          </a>
        </div>
      </div>

      <div v-if="language == 'spanish'" class="column is-auto columns items-justified-right" >
        <div class="column is-narrow" style="text-align: center;">
          <a 
            v-if="spanishMenuPdf != {}"
            class="button is-rounded is-primary is-inline-block" 
            :href="spanishMenuPdf.url"
            target="_blank"
          >
              PDF Menú
          </a>
        </div>
        <div class="column is-narrow" style="text-align: center;">
          <a 
            v-if="spanishAllergenPdf != {}"
            class="button is-rounded is-primary is-inline-block" 
            :href="spanishAllergenPdf.url"
            target="_blank"
          >
              Alérgeno Guía
          </a>
        </div>
      </div>

    </div>

    <div class="pad-5" >
      <div v-if="language == 'english'" v-html="englishDisclaimer" ></div>
      <div v-else v-html="spanishDisclaimer" ></div>
    </div>

    <div 
      v-if="loaded"
      v-masonry 
      transition-duration="0.3s" 
      item-selector=".menu-category"
      class="our-menu columns is-multiline is-mobile"
    >
      <div 
        v-masonry-tile 
        class="menu-category column is-12-mobile is-6-tablet"
        v-for="category in categories"
        :key="category.ID"
      >
        <h2 v-if="language == 'english'" tabindex="0" class="title is-3 is-primary dimbo" style="text-shadow: 2px 2px 0 #a57c09; margin-top: 2rem;" v-html="category.name_english" ></h2>
        <h2 v-if="language == 'spanish'" tabindex="0" class="title is-3 is-primary dimbo" style="text-shadow: 2px 2px 0 #a57c09; margin-top: 2rem;" v-html="category.name_spanish" ></h2>

        <div class="card" style="border: 2px solid #2C5F10" >
          <div v-if="category.photo" class="card-image" tabindex="0" >
            <figure class="image is-16by9">
              <img :src="category.photo.sizes.large ? category.photo.sizes.large : category.photo.url" :alt="category.photo.alt">
            </figure>
          </div>

          <div v-if="language == 'english'">
            <div 
              v-if="category.description_english" 
              class="card-content"
              v-html="category.description_english"
            ></div>
          </div>

          <div v-if="language == 'spanish'">
            <div 
              v-if="category.description_spanish" 
              class="card-content"
              v-html="category.description_spanish"
            ></div>
          </div>

          <menu-item v-for="item in category.items" :key="item.ID" :item="item" :language="language" ></menu-item>

          <div v-if="language == 'english'">
            <div 
              v-if="category.disclaimer_english" 
              class="card-content"
              v-html="category.disclaimer_english"
            ></div>
          </div>

          <div v-if="language == 'spanish'">
            <div 
              v-if="category.disclaimer_spanish" 
              class="card-content"
              v-html="category.disclaimer_spanish"
            ></div>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>
<script>
import MenuItem from './MenuItem.vue'

export default {
  name: "RestaurantMenu",

  components: {
    MenuItem
  },

  props: {
    categories: {
      type: Array,
      default: () => []
    },
    englishDisclaimer: {
      type: String,
      default: ''
    },
    spanishDisclaimer: {
      type: String,
      default: ''
    },
    englishMenuPdf: {
      type: Object,
      default: () => {}
    },
    spanishMenuPdf: {
      type: Object,
      default: () => {}
    },
    englishAllergenPdf: {
      type: Object,
      default: () => {}
    },
    spanishAllergenPdf: {
      type: Object,
      default: () => {}
    },
  },

  data () {
    return {
      loaded: true,
      language: 'english'
    }
  },

  methods: {
    setLanguage(lang) {
      this.loaded = false
      this.language = lang

      let menu = this
      Vue.nextTick(function () {
        menu.loaded = true
      })
    }
  }
}
</script>
