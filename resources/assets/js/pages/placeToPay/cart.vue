<template>
  <div>
    <v-layout row wrap>
      <v-flex xs4 offset-xs4>
        <v-card>
          <v-card-title primary-title>
            <h2>
              Compra actual
            </h2>
          </v-card-title>
          <v-card-text>
            <span>Total productos {{ countProductsAdded }}</span><br>
            <span>Valor Total ${{ cartTotalPrice || 0 }}</span><br>
          </v-card-text>
          <v-card-actions>
            <v-flex xs12 justify-space-around>
              <v-btn @click="remove" flat color="orange">Vaciar carrito</v-btn>
              <router-link :to="{ name: 'checkout'}" class="checkout" >CONTINUAR COMPRA</router-link>
            </v-flex>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'cart-view',
  layout: 'app',

  data() {
    return {

    }
  },

  computed: {
    ...mapGetters(['countProductsAdded', 'cartTotalPrice'])
  },

  methods: {
    remove() {
      this.$store.dispatch('removeAllProducts');
      this.$store.dispatch('responseMessage', {
        type: 'info',
        text: 'Se han removido los productos',
        modal: false
      });
    },
    checkout() {

    }
  }
}
</script>

<style>
.checkout {
  font-size: 16px;
  font-weight: 600;
}
</style>


