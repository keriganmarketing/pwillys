<template>
  <div>
    <form method="post" >
      <div v-if="step == 1" class="columns is-multiline">
        <div class="column is-12">
          <h2 class="title is-secondary">Tell us about yourself.</h2>
          <p class="subtitle is-6">question 1 of 6</p>
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
          <h2 class="title is-secondary">Tell us about your organization.</h2>
          <p class="subtitle is-6">question 2 of 6</p>
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
            <option value="AL">Alabama</option>
            <option value="AK">Alaska</option>
            <option value="AZ">Arizona</option>
            <option value="AR">Arkansas</option>
            <option value="CA">California</option>
            <option value="CO">Colorado</option>
            <option value="CT">Connecticut</option>
            <option value="DE">Delaware</option>
            <option value="DC">District Of Columbia</option>
            <option value="FL">Florida</option>
            <option value="GA">Georgia</option>
            <option value="HI">Hawaii</option>
            <option value="ID">Idaho</option>
            <option value="IL">Illinois</option>
            <option value="IN">Indiana</option>
            <option value="IA">Iowa</option>
            <option value="KS">Kansas</option>
            <option value="KY">Kentucky</option>
            <option value="LA">Louisiana</option>
            <option value="ME">Maine</option>
            <option value="MD">Maryland</option>
            <option value="MA">Massachusetts</option>
            <option value="MI">Michigan</option>
            <option value="MN">Minnesota</option>
            <option value="MS">Mississippi</option>
            <option value="MO">Missouri</option>
            <option value="MT">Montana</option>
            <option value="NE">Nebraska</option>
            <option value="NV">Nevada</option>
            <option value="NH">New Hampshire</option>
            <option value="NJ">New Jersey</option>
            <option value="NM">New Mexico</option>
            <option value="NY">New York</option>
            <option value="NC">North Carolina</option>
            <option value="ND">North Dakota</option>
            <option value="OH">Ohio</option>
            <option value="OK">Oklahoma</option>
            <option value="OR">Oregon</option>
            <option value="PA">Pennsylvania</option>
            <option value="RI">Rhode Island</option>
            <option value="SC">South Carolina</option>
            <option value="SD">South Dakota</option>
            <option value="TN">Tennessee</option>
            <option value="TX">Texas</option>
            <option value="UT">Utah</option>
            <option value="VT">Vermont</option>
            <option value="VA">Virginia</option>
            <option value="WA">Washington</option>
            <option value="WV">West Virginia</option>
            <option value="WI">Wisconsin</option>
            <option value="WY">Wyoming</option>
          </select>
        </div>

        <div class="column is-12">
          <div class="columns is-multiline is-vcentered">
            <div class="column is-narrow">
              <button
                @click.prevent="step--"
                :disabled="!validate"
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
          <h2 class="title is-secondary">What is the event or occasion?</h2>
          <p class="subtitle is-6">question 3 of 6</p>
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
                :disabled="!validate"
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
          <h2 class="title is-secondary">What type of donation suits your needs?</h2>
          <p class="subtitle is-6">question 4 of 6</p>
        </div>

        <div class="column is-6">
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

        <div v-if="formData.donation_type != ''" class="column is-6">

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

        <div v-if="formData.donation_type == 'gift card'" class="column is-6">
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

        <div v-if="formData.donation_type == 'merch'" class="column is-6">
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

        <div v-if="formData.donation_type == 'food'" class="column is-6">
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
                :disabled="!validate"
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
          <h2 class="title is-secondary">Where should we send it?</h2>
          <p class="subtitle is-6">question 5 of 6</p>
        </div>

        <div class="column is-12">
          <div class="columns is-multiline is-vcentered">
            <div class="column is-narrow">
              <button
                @click.prevent="step--"
                :disabled="!validate"
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
          <h2 class="title is-secondary">Is there anything else you'd like us to know?</h2>
          <p class="subtitle is-6">question 6 of 6</p>
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
                :disabled="!validate"
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

export default {
  name: 'Donation Form',
  props: {
    nonce: {
      type: String,
      default: '',
    },
  },
  data () {
    return {
      step: 1,
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
        event: '',
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
        return true
      }

      if(this.step === 3){
        return true
      }

      if(this.step === 4){
        return true
      }

      if(this.step === 5){
        return true
      }

      if(this.step === 6){
        return true
      }
      
    },
    hasError() {
      return this.validate && this.processing
    },
  },
  mounted () {
  },
  methods: {
    submitForm () {
      this.processing = true

      fetch('/wp-json/kma/v1/submit-donation-request', {
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
