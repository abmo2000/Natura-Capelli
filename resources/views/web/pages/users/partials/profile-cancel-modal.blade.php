<div x-show="showCancelModal" x-cloak class="fixed inset-0 z-50" x-transition.opacity>
  <div class="absolute inset-0 bg-black/70" x-on:click="closeCancelModal()"></div>

  <div class="relative min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-gray-900 border border-gray-700 rounded-2xl p-6 shadow-2xl">
      <h4 class="text-white text-xl font-bold mb-2">{{ trans('profile.cancel_modal.title') }}</h4>
      <p class="text-gray-300 mb-6">{{ trans('profile.cancel_modal.message_before_order') }} <span class="font-semibold text-white" x-text="cancelOrderId"></span>?</p>

      <div class="flex gap-3 justify-end">
        <button
          type="button"
          x-on:click="closeCancelModal()"
          class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition"
        >
          {{ trans('profile.cancel_modal.keep_order') }}
        </button>

        <form method="POST" :action="cancelRoute" @submit.prevent="cancelOrder">
          @csrf
          @method('PATCH')
          <input type="hidden" name="order_id" :value="cancelOrderId">
          <button
            type="submit"
            :disabled="cancelLoading"
            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition"
            :class="{ 'opacity-60 cursor-not-allowed': cancelLoading }"
          >
            <span x-show="!cancelLoading">{{ trans('profile.cancel_modal.confirm_cancel') }}</span>
            <span x-show="cancelLoading">{{ trans('profile.cancel_modal.cancelling') }}</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
