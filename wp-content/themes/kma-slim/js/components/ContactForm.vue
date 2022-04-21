<template>
  <div>
    <div v-if="success" id="contact-form-success" >
      <slot />
    </div>
    <div v-else id="contact-form-view" >
      <div class="px-8" >
        <div v-if="hasError" id="contact-form-error" >
          <p
            v-for="error in errors"
            :key="error"
            class="text-error font-bold"
          >{{ error }}</p>
        </div>
        <form class="grid md:grid-cols-2 lg:grid-cols-4 pt-2 lg:pt-4 gap-4" >
          <div>
            <div class="input-wrapper" :class="{ 'border-error': hasError && formData.fname == '' }">
              <label for="first-name" class="sr-only">First Name</label>
              <input
                v-model="formData.fname"
                id="first-name"
                type="text"
                class="text-input md:pl-4"
                placeholder="First Name"
                required
              >
            </div>
          </div>
          <div>
            <div class="input-wrapper" :class="{ 'border-error': hasError && formData.lname == '' }">
              <label for="last-name" class="sr-only">Last Name</label>
              <input
                v-model="formData.lname"
                id="last-name"
                type="text"
                class="text-input md:pl-4"
                placeholder="Last Name"
                required
              >
            </div>
          </div>

          <div>
            <div class="input-wrapper" :class="{ 'border-error': hasError && formData.email == '' }">
              <label for="email-address" class="sr-only">Email Address</label>
              <input
                v-model="formData.email"
                id="email-address"
                type="email"
                class="text-input md:pl-4"
                placeholder="Email Address"
                required
              >
            </div>
          </div>

          <div>
            <div class="input-wrapper" :class="{ 'border-error': hasError && formData.phone == '' }">
              <label for="phone-number" class="sr-only">Phone Number</label>
              <input
                v-model="formData.phone"
                id="phone-number"
                type="tel"
                class="text-input md:pl-4"
                placeholder="Phone Number"
                required
              >
            </div>
          </div>

          <div class="md:col-span-2 lg:col-span-4" >
            <div class="input-wrapper h-32" :class="{ 'border-error': hasError && formData.message == '' }">
              <label for="message" class="sr-only">Message</label>
              <textarea
                type="text"
                id="message"
                v-model="formData.message"
                class="textarea-input h-full w-full p-4"
                placeholder="Type your message here."
                required
              />
            </div>
          </div>

          <div class="md:col-span-2 lg:col-span-4 flex flex-col md:flex-row items-center pt-2" >
            <button
              @click.prevent="submitForm"
              type="submit"
              :disabled="processing"
              class="form-button bg-coral text-white hover:bg-slate"
            >
              Send Message
            </button>
            <div
              v-if="!processing"
              class="text-sm p-4 leading-none"
              :class="{ 'text-error font-bold': this.hasError }" >
              All fields required</div>
            <div
              v-if="processing"
              class="text-sm p-4 leading-none"
              >Sending&nbsp;inquiry.</div>
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
    postId: {
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
        phone: '',
        email: '',
        message: '',
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
        this.formData.phone != '' &&
        this.formData.email != '' &&
        this.formData.message != ''
    },
  },

  mounted () {
    this.formData.post_id = this.postId
    this.formData.user_ip = this.userIp
    this.formData.user_agent = this.userAgent
    this.formData.referrer = this.referrer
  },

  methods: {
    submitForm () {
      this.processing = true

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
