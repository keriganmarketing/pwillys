<template>
  <div>
    <div v-if="success" id="contact-form-success" >
      <p>Thanks for reaching out. One of our staff members will get back with you as soon as possible.</p>
    </div>
    <div v-else id="contact-form-view" >
      <div class="contact-form" >
        <div v-if="hasError" id="contact-form-error" >
          <p
            v-for="error in errors"
            :key="error"
            class="has-text-danger is-bold"
          >{{ error }}</p>
        </div>
        <form >
          <div class="columns is-multiline">

            <div class="column is-6" >
              <label for="first-name" class="sr-only">First Name</label>
              <input
                v-model="formData.fname"
                id="first-name"
                type="text"
                class="input"
                placeholder="First Name"
                required
              >
            </div>
 
            <div class="column is-6" >
              <label for="last-name" class="sr-only">Last Name</label>
              <input
                v-model="formData.lname"
                id="last-name"
                type="text"
                class="input" 
                placeholder="Last Name"
                required
              >
            </div>

            <div class="column is-12" >
              <label for="email-address" class="sr-only">Email Address</label>
              <input
                v-model="formData.email"
                id="email-address"
                type="email"
                class="input email" 
                :class="{ 'border-error': hasError && formData.email == '' }"
                placeholder="Email Address"
                required
              >
            </div>

            <div class="column is-12">
              <label for="comments" class="sr-only">Message</label>
              <textarea
                type="text"
                id="comments"
                v-model="formData.comments"
                class="textarea" 
                :class="{ 'border-error': hasError && formData.comments == '' }"
                placeholder="Type your message here."
                required
              />
            </div>

            <div class="column is-12 columns" >
              <div class="column is-narrow">
                <button
                  @click.prevent="submitForm"
                  type="submit"
                  :disabled="processing"
                  class="button is-primary is-rounded has-shadow" 
                >
                  Send Message
                </button>
              </div>
              <em
                v-if="processing"
                class="column is-narrow is-inline"
                >Sending&nbsp;inquiry.</em>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'ContactForm',
  props: {
    nonce: {
      type: String,
      default: '',
    },
    userIp: {
      type: String,
      default: '',
    },
    userAgent: {
      type: String,
      default: '',
    },
    referrer: {
      type: String,
      default: '',
    }
  },

  data () {
    return {
      processing: false,
      errors: {},
      errorCode: null,
      success: false,
      hasError: false,
      formData: {
        fname: '',
        lname: '',
        email: '',
        comments: '',
      },
      form: '',
      mailto: '',
      submitted: false,
    }
  },

  computed: {
    validate() {
      return this.formData.fname != '' &&
        this.formData.lname != '' &&
        this.formData.email != '' &&
        this.formData.comments != ''
    },
  },

  mounted () {
    this.formData.user_ip = this.userIp
    this.formData.user_agent = this.userAgent
    this.formData.referrer = this.referrer
  },

  methods: {
    submitForm () {
      this.processing = true

      if(!this.validate){
        this.hasError = true
        this.processing = false
        return null
      }

      fetch('/wp-json/kma/v1/submit-contact-form', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': this.nonce
        },
        body: JSON.stringify(this.formData)
      })
        .then(r => r.json())
        .then((res) => {
          if(res.success){
            this.success = true
            dataLayer.push({'event': 'contact_form_submitted'});
          } else {
            this.success  = false
            this.hasError = true
            this.errors   = res.data
          }

          this.processing   = false
        })
    }
  }
}
</script>
<style scoped>
.border-error {
  border: 1px solid red;
  border-radius: 0.5rem;
}
</style>