<template>
    <v-app dark id="panel" standalone v-cloak>
        <left-side-bar></left-side-bar>
        <app-nav-bar :title="title"></app-nav-bar>
        <main>
            <transition>
                <v-container>
                    <slot></slot>
                </v-container>
            </transition>

        </main>
        <fab-button></fab-button>
        <app-footer></app-footer>
    </v-app>
</template>
<script>
import AppFooter from '~partials/AppFooter.vue'
import AppNavBar from '~partials/AppNavBar.vue'
import LeftSideBar from '~partials/LeftSideBar.vue'
import FabButton from '~components/FabButton.vue'
export default {
    data() {
        return {
            title: 'Wykresy'
        }
    },
    components: {
        AppFooter,
        AppNavBar,
        LeftSideBar,
        FabButton
    }
}
</script>