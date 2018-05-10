<template>
  <div>
    <v-layout row wrap>
      <v-flex xs6 offset-xs3>
        <v-card>
          <v-card-title primary-title>
            <h3>
              Resultado de su transacción:
            </h3>
          </v-card-title>
          <v-card-text>
            <div v-if="loading">
              <v-progress-circular indeterminate color="primary"></v-progress-circular>
            </div>
            <div v-else>
              <span> {{ result }} </span><br><br>
              <router-link :to=" { name: 'myOrders' } ">Ir a mis órdenes</router-link>
            </div>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
import axios from 'axios'
import Form from 'vform'

export default {
  name: 'callback-view',

  data () {
    return {
      form: new Form({
        transactionID: ''
      }),
      result: '',
      loading: false,
    }
  },

  created () {
    this.form.transactionID = this.$store.getters.getCurrentTransactionId;
    console.log(this.$store.getters)
    this.transactionInfo();
  },

  methods: {
    async transactionInfo() {
      this.loading = true;
      const { data } = await this.form.post('/api/transaction/info');
      this.result = data.result.responseReasonText;
      this.loading = false;

      if (data.result.transactionState === 'OK') {
        this.removeProducts();
      }

    },

    removeProducts() {
      this.$store.dispatch('removeAllProducts');
      this.$store.dispatch('responseMessage', {
        type: 'success',
        text: 'Su compra ha sido procesada correctamente',
        modal: false
      });
    },
  }
}
</script>

<style>

</style>


