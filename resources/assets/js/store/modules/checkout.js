import * as types from '../mutation-types'

// state
export const state = {
  bankList: []
}

// mutations
export const mutations = {
  [types.FETCH_BANK_LIST] (state, { bankList }) {
    this.state.bankList = bankList
  }
}

// actions
export const actions = {

}

// getters
export const getters = {

}
