<template>
  <div>
    <v-layout column>
    <v-flex xs12>
      <v-card>
        <v-container fluid grid-list-md>
          <v-layout row wrap>
            <v-flex
              v-for="product in products"
              v-bind="{ [`xs3`]: true }"
              :key="product.code"
            >
              <v-card>
                <v-card-media
                  :src="product.image_path"
                  height="200px"
                  :contain="true"
                >
                </v-card-media>
                <v-card-text>
                  <span>{{ product.name }}</span><br>
                  <span>${{ product.price }}</span><br>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn v-if="!productIsInCart(product.code)" @click="addToCart(product.code)" icon>
                    <v-icon color="black" >add_shopping_cart</v-icon>
                  </v-btn>
                  <div class="spacing" v-else >

                  </div>
                </v-card-actions>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card>
    </v-flex>
  </v-layout>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'products-view',
  layout: 'app',

  data() {
    return {
      products: [
        { code: '001', name: 'Producto 1', description: 'Descripcion de producto', price: '13200', image_path: require('~/assets/img/prod1.jpg') },
        { code: '002', name: 'Producto 2', description: 'Descripcion de producto', price: '52000', image_path: require('~/assets/img/prod2.jpg') },
        { code: '003', name: 'Producto 3', description: 'Descripcion de producto', price: '69500', image_path: require('~/assets/img/prod3.jpg') },
        { code: '004', name: 'Producto 4', description: 'Descripcion de producto', price: '36000', image_path: require('~/assets/img/prod4.jpg') },
        { code: '005', name: 'Producto 5', description: 'Descripcion de producto', price: '10000', image_path: require('~/assets/img/prod5.jpg') },
        { code: '006', name: 'Producto 6', description: 'Descripcion de producto', price: '15000', image_path: require('~/assets/img/prod6.jpg') },
      ]
    }
  },

  computed: {
    ...mapGetters(['productIsInCart'])
  },

  methods: {
    addToCart(productCode) {
      this.$store.dispatch('addProductToCart', { code: productCode, price: this.products.find(product => product.code === productCode).price });
      this.$store.dispatch('responseMessage', {
        type: 'success',
        text: `Se ha agregado correctamente el producto ${this.products.find(product => product.code === productCode).name}`,
        modal: false
      })
    }
  }
}

</script>

<style>
  .spacing {
    margin-top: 36px;
  }
</style>


