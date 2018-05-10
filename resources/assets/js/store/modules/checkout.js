import * as types from '../mutation-types'

// state
export const state = {
  bankList: [],
  transactionId: ''
}

// mutations
export const mutations = {
  [types.FETCH_BANK_LIST] (state, { bankList }) {
    state.bankList = bankList
  },
  [types.ADD_TRANSACTION_ID] (state, { transactionId }) {
    state.transactionId = transactionId
  }
}

// actions
export const actions = {
  async addTransactionId ({ commit }, payload) {
    commit(types.ADD_TRANSACTION_ID, payload)
  }
}

// getters
export const getters = {
  getCurrentTransactionId: state => state.transactionId
}
