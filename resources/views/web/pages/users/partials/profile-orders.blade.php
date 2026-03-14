<div x-show="isActive('orders')" x-cloak class="profile-tab-panel">
  <h3 class="text-2xl text-white font-bold mb-6">{{ trans('profile.orders.title') }}</h3>

  <div x-show="cancelSuccessMessage" x-transition class="mb-4 bg-green-900/40 border border-green-700 text-green-200 px-4 py-3 rounded-lg" x-text="cancelSuccessMessage"></div>
  <div x-show="cancelErrorMessage" x-transition class="mb-4 bg-red-900/40 border border-red-700 text-red-200 px-4 py-3 rounded-lg" x-text="cancelErrorMessage"></div>

  @if(session('order_status'))
    <div class="mb-4 bg-green-900/40 border border-green-700 text-green-200 px-4 py-3 rounded-lg">
      {{ session('order_status') }}
    </div>
  @endif

  @if(session('order_error'))
    <div class="mb-4 bg-red-900/40 border border-red-700 text-red-200 px-4 py-3 rounded-lg">
      {{ session('order_error') }}
    </div>
  @endif

  @if($orders->count())
    <div class="overflow-x-auto border border-gray-700 rounded-xl">
      <table class="min-w-full divide-y divide-gray-700">
        <thead class="bg-gray-900">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">{{ trans('profile.orders.order_id') }}</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">{{ trans('profile.orders.date') }}</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">{{ trans('profile.orders.status') }}</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">{{ trans('profile.orders.amount') }}</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">{{ trans('profile.orders.actions') }}</th>
          </tr>
        </thead>
        <tbody class="bg-gray-800 divide-y divide-gray-700">
          @foreach($orders as $order)
            <tr class="hover:bg-gray-750 transition">
              <td class="px-4 py-3 text-sm text-white">{{ $order->order_id ?? ('#' . $order->id) }}</td>
              <td class="px-4 py-3 text-sm text-gray-300">{{ $order->created_at?->format('Y-m-d h:i A') }}</td>
              <td class="px-4 py-3 text-sm">
                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-orange-500/20 text-orange-300 uppercase">
                  {{ $order->status }}
                </span>
              </td>
              <td class="px-4 py-3 text-sm text-white">{{ trans('profile.orders.currency_prefix') }} {{ number_format((float) $order->amount, 2) }}</td>
              <td class="px-4 py-3 text-sm text-white">
                @can('cancelOrder', $order)
                  <button
                    type="button"
                    x-on:click="openCancelModal('{{ $order->order_id }}')"
                    class="bg-red-600 hover:bg-red-700 text-white text-xs font-semibold py-2 px-3 rounded-lg transition"
                  >
                    {{ trans('profile.orders.cancel_order') }}
                  </button>
                @else
                  <span class="text-gray-400 text-xs">-</span>
                @endcan
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-6 [&_nav]:text-gray-200 [&_span]:text-gray-300">
      {{ $orders->links() }}
    </div>
  @else
    <div class="bg-gray-900 border border-gray-700 rounded-xl p-6 text-center text-gray-300">
      {{ trans('profile.orders.empty') }}
    </div>
  @endif
</div>
