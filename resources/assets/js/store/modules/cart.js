import * as types from '../mutation-types'

// state
export const state = {
  productsAdded: [],
  checkoutStatus: null
}

// mutations
export const mutations = {
  [types.ADD_PRODUCT_TO_CART] (state, { code, price }) {
    state.productsAdded.push({
      productCode: code,
      price,
      quantity: 1
    })
  },
  [types.SET_CART_PRODUCTS] (state, { products }) {
    state.productsAdded = products
  },
  [types.SET_CHECKOUT_STATUS] (state, { status }) {
    state.checkoutStatus = status
  },
  [types.REMOVE_ALL_PRODUCTS] (state) {
    state.productsAdded = []
    state.checkoutStatus = null
  }
}

// actions
export const actions = {
  async addProductToCart ({ commit }, payload) {
    commit(types.ADD_PRODUCT_TO_CART, payload)
  },

  async removeAllProducts ({ commit }) {
    commit(types.REMOVE_ALL_PRODUCTS)
  }

}

// getters
export const getters = {
  productsAdded: state => state.productsAdded,
  countProductsAdded: state => state.productsAdded.length,
  checkoutStatus: state => state.checkoutStatus,
  productIsInCart: (state) => (code) => state.productsAdded.find(product => product.productCode === code),
  cartTotalPrice: (state) => {
    return state.productsAdded.reduce((total, product) => {
      return total + parseInt(product.price)
    }, 0)
  }
}
