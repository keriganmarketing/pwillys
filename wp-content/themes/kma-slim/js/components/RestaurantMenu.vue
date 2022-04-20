<template>
  <div class="section">
    <br>
    <div class="columns is-full-width">
      <div class="column has-text-right pad-5 is-half">
        <button @click="language = 'english'" class="button is-rounded" :class="{ 'is-secondary': language == 'english', 'is-primary': language == 'spanish' }" >
          <span v-if="language=='english'" >English</span>
          <span v-else>Espanol</span>
        </button>
      </div>
      <div class="column has-text-left pad-5 is-half">
        <button @click="language = 'spanish'" class="button is-rounded" :class="{ 'is-secondary': language == 'spanish', 'is-primary': language == 'english' }" >
          <span v-if="language=='english'" >Spanish</span>
          <span v-else>Spanish</span>
        </button>
      </div>
    </div>

    <div 
      v-masonry 
      transition-duration="0.3s" 
      item-selector=".menu-category"
      class="our-menu columns is-multiline is-mobile"
    >
      <div 
        v-masonry-tile 
        class="menu-category column is-12-mobile is-6-tablet is-4-widescreen"
        v-for="category in categories"
        :key="category.ID"
      >
        <h2 v-if="language == 'english'" tabindex="0" class="title is-2 dimbo" v-html="category.name_english" ></h2>
        <h2 v-if="language == 'spanish'" tabindex="0" class="title is-2 dimbo" v-html="category.name_spanish" ></h2>

        <div class="card" >
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
      type: Object,
      default: () => {}
    }
  },

  data () {
    return {
      language: 'english'
    }
  }
}
</script>
