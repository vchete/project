<template>
    <div>
        <transition name="fade">
            <div class="alert-custom" v-show="show">
                <div class="alert" :class="typeAlert" role="alert">
                    <span v-html="message"></span>
                    <a type="button" class="close" @click="mHide">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        template:'#alert',
        data () {
            return {
                typeAlert: null,
                message: null,
                show: false
            }
        },
        methods: {
            mShow (message, type = 'success') {
                this.typeAlert = 'alert-' + type;
                this.message   = message;
                this.show      = true;
                setTimeout(() => this.mHide(), 4000);
            },
            mHide () {
                this.typeAlert = null;
                this.message   = null;
                this.show      = false;
            }
        }
    }
</script>

<style lang="sass" scoped>
    .fade-enter-active, .fade-leave-active
        transition: opacity .5s
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
        opacity: 0
    .alert-custom
        position: absolute
        width: 30%
        averflow: visible
        z-index: 1982
        right:10px
    .alert-warning
        color: white !important
        background-color: #f1b44c !important
    .alert-success
        color: white !important
        background-color: #11c46e !important
    .alert-danger
        color: white !important
        background-color: #fb4d53 !important
    @media (max-width: 992px)
        .alert-custom
            width: 50%
    @media (max-width: 576px)
        .alert-custom
            width: 80%
</style>