<template>
  <v-app light>
    <transition name="page" mode="out-in">
      <router-view></router-view>
    </transition>
    <feedback-message></feedback-message>
    <page-footer></page-footer>
  </v-app>
</template>

<script>
import { mapGetters } from 'vuex'

import FeedbackMessage from '~/components/FeedbackMessage'
import PageFooter from '~/components/PageFooter'

export default {
  name: 'default-layout',
  components: {
    'feedback-message': FeedbackMessage,
    'page-footer': PageFooter
  }
}
</script>
