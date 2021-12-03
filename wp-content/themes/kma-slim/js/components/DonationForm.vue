<template>
  <div>
    <div class="" >
    </div>
    <message
      v-if="success" 
      title="Success" 
      class="is-success"
    >
      Thank you for reaching out to us. We love supporting great causes and organizations. We try to facilitate as many requests as we can and appreciate that you thought of us. Someone will reach out to you to let you know if we are able to contribute!
    </message>
    <form method="post" v-else >
      <div v-if="step == 1" class="columns is-multiline">
        <div class="column is-12">
          <h2 class="title is-primary dimbo is-2">Complete our Online Donation Request form.</h2>
          <p class="subtitle is-6">question 1 of 7</p>
        </div>
        <div class="column is-6">
          <label for="first-name-text">First Name</label>
          <input id="first-name-text" type="text" v-model="formData.fname" class="input"  required>
        </div>
        <div class="column is-6">
          <label for="last-name-text">Last Name</label>
          <input id="last-name-text" type="text" v-model="formData.lname" class="input"  required>
        </div>
        <div class="column is-6">
          <label for="email-address-text">Email Address</label>
          <input id="email-address-text" type="email" v-model="formData.email" class="input email"  required>
        </div>
        <div class="column is-6">
          <label for="phone-text">Phone Number</label>
          <input id="phone-text" type="text" v-model="formData.phone" class="input"  required>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline is-vcentered">
            <div class="column is-narrow">
              <button
                @click.prevent="step++"
                :disabled="!validate"
                class="button is-primary is-rounded"
              >
                Next
              </button>
            </div>
            <em
              class="column is-narrow is-inline"
              >All&nbsp;fields&nbsp;required.</em>
          </div>
        </div>
      </div>

      <div v-if="step == 2" class="columns is-multiline">
        <div class="column is-12">
          <h2 class="title is-primary dimbo is-2">Tell us about your organization.</h2>
          <p class="subtitle is-6">question 2 of 7</p>
        </div>

        <div class="column is-12">
          <label for="org_name-text">Name of Organization</label>
          <input id="org_name-text" type="text" v-model="formData.org_name" class="input"  required>
        </div>
        <div class="column is-6">
          <label for="org_city-text">City</label>
          <input id="org_city-text" type="text" v-model="formData.org_city" class="input"  required>
        </div>
        <div class="column is-6">
          <label for="org_state-text">State</label>
          <select
            v-model="formData.org_state"
            id="org_state-text"
            class="input select"
            required
          >
            <option 
              v-for="state in states" 
              :key="state.abbreviation" 
              :value="state.abbreviation" 
            >{{ state.name }}</option>
          </select>
        </div>

        <div class="column is-12">
          <div class="columns is-multiline is-vcentered">
            <div class="column is-narrow">
              <button
                @click.prevent="step--"
                class="button is-primary is-rounded"
              >
                Back
              </button>
              &nbsp;
              <button
                @click.prevent="step++"
                :disabled="!validate"
                class="button is-primary is-rounded"
              >
                Next
              </button>
            </div>
            <em
              class="column is-narrow is-inline"
              >All&nbsp;fields&nbsp;required.</em>
          </div>
        </div>
      </div>

      <div v-if="step == 3" class="columns is-multiline">
        <div class="column is-12">
          <h2 class="title is-primary dimbo is-2">What is the event or occasion?</h2>
          <p class="subtitle is-6">question 3 of 7</p>
        </div>

        <div class="column is-6">
          <label for="event_name-text">Name of Event</label>
          <input id="event_name-text" type="text" v-model="formData.event_name" class="input"  required>
        </div>
        <div class="column is-6">
          <label for="event_date-text">Event Date</label>
          <input id="event_date-text" type="date" v-model="formData.event_date" class="input"  required>
        </div>
        <div class="column is-12">
          <label for="event_desc-text">Event description</label>
          <textarea id="event_desc-text" v-model="formData.event_desc" class="textarea"  required></textarea>
        </div>

        <div class="column is-12">
          <div class="columns is-multiline is-vcentered">
            <div class="column is-narrow">
              <button
                @click.prevent="step--"
                class="button is-primary is-rounded"
              >
                Back
              </button>
              &nbsp;
              <button
                @click.prevent="step++"
                :disabled="!validate"
                class="button is-primary is-rounded"
              >
                Next
              </button>
            </div>
            <em
              class="column is-narrow is-inline"
              >All&nbsp;fields&nbsp;required.</em>
          </div>
        </div>
      </div>

      <div v-if="step == 4" class="columns is-multiline">
        <div class="column is-12">
          <h2 class="title is-primary dimbo is-2">What type of donation suits your needs?</h2>
          <p class="subtitle is-6">question 4 of 7</p>
        </div>

        <div class="column is-4">
          <label for="donation_type-text">Select Donation Type</label>
          <select
            v-model="formData.donation_type"
            id="donation_type-text"
            class="input select"
            required
          >
            <option value="gift card">Gift Card Request</option>
            <option value="merch">Merch Giveaway</option>
            <option value="food">Food Donation</option>
          </select>
        </div>

        <div v-if="formData.donation_type != ''" class="column is-4">

          <label 
            v-if="formData.donation_type == 'gift card'" 
            for="how_many-text"
          >How many do you need?</label>
          <label 
            v-if="formData.donation_type == 'merch'" 
            for="how_many-text"
          >How many do you need?</label>
          <label 
            v-if="formData.donation_type == 'food'" 
            for="how_many-text"
          >How many people do you need to feed?</label>

          <input id="how_many-text" type="number" v-model="formData.how_many" class="input"  required>
        </div>

        <div v-if="formData.donation_type == 'gift card'" class="column is-4">
          <label for="card_amount-text">What amount on the card(s)?</label>
          <select
            v-model="formData.card_amount"
            id="card_amount-text"
            class="input select"
            required
          >
            <option value="$25">$25</option>
            <option value="$50">$50</option>
            <option value="$100">$100</option>
          </select>
        </div>

        <div v-if="formData.donation_type == 'merch'" class="column is-4">
          <label for="merch_type-text">What type of merch?</label>
          <select
            v-model="formData.merch_type"
            id="merch_type-text"
            class="input select"
            required
          >
            <option value="hats">Hats</option>
            <option value="shirts">Shirts</option>
            <option value="koozies">Koozies</option>
          </select>
        </div>

        <div v-if="formData.donation_type == 'food'" class="column is-4">
          <label for="food_type-text">What type of food?</label>
          <select
            v-model="formData.food_type"
            id="food_type-text"
            class="input select"
            required
          >
            <option value="entres">Entres</option>
            <option value="sandwiches">Sandwiches</option>
            <option value="appetizers">Appetizers</option>
          </select>
        </div>

        <div class="column is-12">
          <div class="columns is-multiline is-vcentered">
            <div class="column is-narrow">
              <button
                @click.prevent="step--"
                class="button is-primary is-rounded"
              >
                Back
              </button>
              &nbsp;
              <button
                @click.prevent="step++"
                :disabled="!validate"
                class="button is-primary is-rounded"
              >
                Next
              </button>
            </div>
            <em
              class="column is-narrow is-inline"
              >All&nbsp;fields&nbsp;required.</em>
          </div>
        </div>
      </div>

      <div v-if="step == 5" class="columns is-multiline">
        <div class="column is-12">
          <h2 class="title is-primary dimbo is-2">What is your mailing address?</h2>
          <p class="subtitle is-6">question 5 of 7</p>
        </div>

        <div class="column is-8">
          <label for="address_street">Street</label>
          <input id="address_street" type="text" v-model="address_street" class="input"  required>
        </div>
        <div class="column is-4">
          <label for="address_apt">Apt / Suite</label>
          <input id="address_apt" type="text" v-model="address_apt" class="input"  required>
        </div>
        <div class="column is-4">
          <label for="address_city">City</label>
          <input id="address_city" type="text" v-model="address_city" class="input"  required>
        </div>
        <div class="column is-4">
          <label for="address_state">State</label>
          <select
            v-model="address_state"
            id="address_state"
            class="input select"
            required
          >
            <option 
              v-for="state in states" 
              :key="state.abbreviation" 
              :value="state.abbreviation" 
            >{{ state.name }}</option>
          </select>
        </div>
        <div class="column is-4">
          <label for="address_zip">Zip</label>
          <input id="address_zip" type="text" v-model="address_zip" class="input"  required>
        </div>

        <div class="column is-12">
          <div class="columns is-multiline is-vcentered">
            <div class="column is-narrow">
              <button
                @click.prevent="step--"
                class="button is-primary is-rounded"
              >
                Back
              </button>
              &nbsp;
              <button
                @click.prevent="step++"
                :disabled="!validate"
                class="button is-primary is-rounded"
              >
                Next
              </button>
            </div>
            <em
              class="column is-narrow is-inline"
              >All&nbsp;fields&nbsp;required.</em>
          </div>
        </div>
      </div>

      <div v-if="step == 6" class="columns is-multiline">
        <div class="column is-12">
          <h2 class="title is-primary dimbo is-2">Please provide us with your donation form.</h2>
          <p class="subtitle is-6">question 6 of 7</p>
        </div>

        <div class="column is-6">
          <label for="form_type">Select the type of form.</label>
          <select
            v-model="form_type"
            id="form_type"
            class="input select"
            required
          >
            <option value="file">PDF file</option>
            <option value="url">Online form</option>
          </select>
        </div>

        <div v-if="form_type == 'file'" class="column is-6">
          <label for="donation_file-pdf">Select a PDF file on your computer.</label>
          <input 
            id="donation_file-pdf" 
            type="file" 
            class="ipnut file" 
            @change="fileChange($event.target.name, $event.target.files)"
            accept="application/pdf"
          >
        </div>

        <div v-if="form_type == 'url'" class="column is-6">
          <label for="donation_file-url">Provide the website URL to your form.</label>
          <input id="donation_file-url" type="text" v-model="formData.donation_file" class="input"  required>
        </div>

        <div class="column is-12">
          <div class="columns is-multiline is-vcentered">
            <div class="column is-narrow">
              <button
                @click.prevent="step--"
                class="button is-primary is-rounded"
              >
                Back
              </button>
              &nbsp;
              <button
                @click.prevent="step++"
                :disabled="!validate"
                class="button is-primary is-rounded"
              >
                Next
              </button>
            </div>
            <em
              class="column is-narrow is-inline"
              >All&nbsp;fields&nbsp;required.</em>
          </div>
        </div>
      </div>

      <div v-if="step == 7" class="columns is-multiline">  
        <div class="column is-12">
          <h2 class="title is-primary dimbo is-2">Is there anything else you'd like us to know?</h2>
          <p class="subtitle is-6">question 7 of 7</p>
        </div>

        <div class="column is-12">
          <label for="message-text">Additional Information</label>
          <textarea id="message-text" class="textarea" v-model="formData.comments" ></textarea>
        </div>

        <div class="column is-12">
          <div class="columns is-multiline is-vcentered">
            <div class="column is-narrow">
              <button
                @click.prevent="step--"
                class="button is-primary is-rounded"
              >
                Back
              </button>
              &nbsp;
              <button
                @click.prevent="submitForm"
                type="submit"
                :disabled="processing || !validate"
                class="button is-primary is-rounded"
              >
                Complete Donation Request
              </button>
            </div>
            <em
              v-if="processing"
              class="column is-narrow is-inline"
              >Submitting&nbsp;request.</em>
          </div>
        </div>
      </div>

    </form>
  </div>
</template>
<script>
import Message from './message.vue'

export default {
  name: 'DonationForm',
  components: {
    message: Message
  },
  props: {
    nonce: {
      type: String,
      default: '',
    },
  },
  data () {
    return {
      step: 1,
      form_type: '',
      processing: false,
      error_message: '',
      errorCode: null,
      success: false,
      formData: {
        fname: '',
        lname: '',
        phone: '',
        email: '',
        comments: '',
        org_name: '',
        org_city: '',
        org_state: '',
        event_name: '',
        event_date: '',
        event_desc: '',
        donation_type: '',
        how_many: '',
        card_amount: '',
        merch_type: '',
        food_type: '',
        mailing_address: '',
        donation_file: '',
      },
      token: '',
      form: '',
      mailto: '',
      submitted: false,
      states: [
        { name: "Alabama", abbreviation: "AL" },
        { name: "Alaska", abbreviation: "AK" },
        { name: "Arizona", abbreviation: "AZ" },
        { name: "Arkansas", abbreviation: "AR" },
        { name: "California", abbreviation: "CA" },
        { name: "Colorado", abbreviation: "CO" },
        { name: "Connecticut", abbreviation: "CT" },
        { name: "Delaware", abbreviation: "DE" },
        { name: "Florida", abbreviation: "FL" },
        { name: "Georgia", abbreviation: "GA" },
        { name: "Hawaii", abbreviation: "HI" },
        { name: "Idaho", abbreviation: "ID" },
        { name: "Illinois", abbreviation: "IL" },
        { name: "Indiana", abbreviation: "IN" },
        { name: "Iowa", abbreviation: "IA" },
        { name: "Kansas", abbreviation: "KS" },
        { name: "Kentucky", abbreviation: "KY" },
        { name: "Louisiana", abbreviation: "LA" },
        { name: "Maine", abbreviation: "ME" },
        { name: "Maryland", abbreviation: "MD" },
        { name: "Massachusetts", abbreviation: "MA" },
        { name: "Michigan", abbreviation: "MI" },
        { name: "Minnesota", abbreviation: "MN" },
        { name: "Mississippi", abbreviation: "MS" },
        { name: "Missouri", abbreviation: "MO" },
        { name: "Montana", abbreviation: "MT" },
        { name: "Nebraska", abbreviation: "NE" },
        { name: "Nevada", abbreviation: "NV" },
        { name: "New Hampshire", abbreviation: "NH" },
        { name: "New Jersey", abbreviation: "NJ" },
        { name: "New Mexico", abbreviation: "NM" },
        { name: "New York", abbreviation: "NY" },
        { name: "North Carolina", abbreviation: "NC" },
        { name: "North Dakota", abbreviation: "ND" },
        { name: "Ohio", abbreviation: "OH" },
        { name: "Oklahoma", abbreviation: "OK" },
        { name: "Oregon", abbreviation: "OR" },
        { name: "Palau", abbreviation: "PW" },
        { name: "Pennsylvania", abbreviation: "PA" },
        { name: "Puerto Rico", abbreviation: "PR" },
        { name: "Rhode Island", abbreviation: "RI" },
        { name: "South Carolina", abbreviation: "SC" },
        { name: "South Dakota", abbreviation: "SD" },
        { name: "Tennessee", abbreviation: "TN" },
        { name: "Texas", abbreviation: "TX" },
        { name: "Utah", abbreviation: "UT" },
        { name: "Vermont", abbreviation: "VT" },
        { name: "Virgin Islands", abbreviation: "VI" },
        { name: "Virginia", abbreviation: "VA" },
        { name: "Washington", abbreviation: "WA" },
        { name: "West Virginia", abbreviation: "WV" },
        { name: "Wisconsin", abbreviation: "WI" },
        { name: "Wyoming", abbreviation: "WY" }
      ],
      address_street: '',
      address_apt: '',
      address_city: '',
      address_state: '',
      address_zip: '',
      pdf_file: ''
    }
  },
  computed: {
    validate() {

      if(this.step === 1){
        return this.formData.fname != '' &&
          this.formData.lname != '' &&
          this.formData.email != '' &&
          this.formData.phone != ''
      }

      if(this.step === 2){
        return this.formData.org_name != '' &&
          this.formData.org_city != '' &&
          this.formData.org_state != ''
      }

      if(this.step === 3){
        return this.formData.event_name != '' &&
          this.formData.event_date != '' &&
          this.formData.event_desc != ''
      }

      if(this.step === 4){
        return this.formData.donation_type != '' &&
          this.formData.how_many != '' && (
            this.formData.card_amount != '' ||
            this.formData.merch_type != '' ||
            this.formData.food_type != ''
          )
      }

      if(this.step === 5){
        return this.formData.mailing_address != ''
      }

      if(this.step === 6){
        return this.formData.donation_file != ''
      }

      if(this.step === 7){
        return true
      }
      
    },
    fullAddress() {
      return this.address_street + ' ' + this.address_apt + ', ' + this.address_city + ', '  + this.address_state + ' ' + this.address_zip
    }
  },
  watch: {
    fullAddress(){
      this.formData.mailing_address = this.fullAddress
    }
  },
  mounted () {
  },
  methods: {
    fileChange (target, files) {
      let uploader = this
      var fileToLoad = files[0];
      var fileReader = new FileReader();
      var base64;
      // Onload of file read the file content
      fileReader.onload = function(fileLoadedEvent) {
          base64 = fileLoadedEvent.target.result;
          console.log(base64); 
          uploader.formData.donation_file = base64
      };
      fileReader.readAsDataURL(fileToLoad);

      console.log(this.formData.donation_file); 
      this.pdf_file = files[0]
    },
    submitForm () {
      this.processing = true

      fetch('/wp-json/kma/v1/submit-donation-request', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        headers: {
          // 'Content-Type': 'multipart/form-data; charset=utf-8; boundary=' + Math.random().toString().substr(2),
          'Content-Type': 'application/json',
          'X-WP-Nonce': this.nonce
        },
        body: JSON.stringify(this.formData)
      })
        .then(r => r.json())
        .then((res) => {
          console.log(res)
          this.success = true
          this.processing   = false
        })
        .catch(err => {
          this.hasError     = true
          this.errorMessage = err.message
          this.errorCode    = err.code
          this.processing   = false
        })
    },
  }
}
</script>
