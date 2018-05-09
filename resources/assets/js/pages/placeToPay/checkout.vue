<template>
  <div>
    <v-layout row wrap>
      <v-flex xs6 offset-xs3>
        <form @submit.prevent="beginTransaction" @keydown="form.onKeydown($event)">
          <v-card>
            <v-card-title primary-title>
              <h3 class="headline mb-0">
                Por favor elija los datos de pago
              </h3>
            </v-card-title>
            <v-card-text>
              <select-input
                :form="checkout_form"
                :items="tranType"
                :label="'Tipo de cuenta para realizar pago:'"
                :v-errors="errors"
                :value.sync="checkout_form.user_type"
                name="user_type"
                v-validate="'required'"
              ></select-input>

              <select-input
                :form="checkout_form"
                :items="bankList"
                :label="'A continuación seleccione su banco:'"
                :v-errors="errors"
                :value.sync="checkout_form.bank"
                name="bank"
                v-validate="'required|not_in:0'"
              ></select-input>

              <v-card-actions>
                <submit-button :color="'primary'" block :form="checkout_form" :label="'Continuar al banco'"></submit-button>
              </v-card-actions>

            </v-card-text>
          </v-card>
        </form>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Form from 'vform'

export default {
  name: 'ckechout-view',
  data () {
    return {
      tranType: [
        {value: '0', text: 'Persona'},
        {value: '1', text: 'Empresas'}
      ],
      bankList: [],
      checkout_form: new Form({
        user_type: '',
        bank: '',
        cartTotalPrice: 0,
      })
    }
  },

  created () {
    window.config.bankList
    .filter(bank => bank.bankCode !== '0')
    .filter(bank => bank.bankCode === '1022')
    .forEach(bank => {
      this.bankList.push(
        {value: bank.bankCode, text: bank.bankName}
      )
    });

    this.checkout_form.cartTotalPrice = this.$store.getters.cartTotalPrice;

  },

  methods: {
    async beginTransaction() {
      if (await this.formHasErrors()) return

      const { data } = await this.checkout_form.post('/api/beginTransaction')

      const url = data.result.bankURL;
      console.log(url)
      window.open(url);
    }
  }

}
</script>

<style>

</style>


