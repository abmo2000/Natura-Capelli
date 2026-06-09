<div x-show="isActive('personal')" class="profile-tab-panel space-y-6">
  <h3 class="text-2xl text-white font-bold">{{ trans('profile.tabs.personal_info') }}</h3>

  <div x-show="successMessage" x-transition class="bg-green-900/40 border border-green-700 text-green-200 px-4 py-3 rounded-lg" x-text="successMessage"></div>
  <div x-show="errorMessage" x-transition class="bg-red-900/40 border border-red-700 text-red-200 px-4 py-3 rounded-lg" x-text="errorMessage"></div>

  <form @submit.prevent="submitProfile" class="space-y-5">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label for="name" class="block text-gray-300 text-sm font-medium mb-2">{{ trans('profile.form.name') }}</label>
        <input
          type="text"
          id="name"
          name="name"
          x-model="form.name"
          @blur="validateField('name')"
          required
          class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
          :class="{ 'border-red-500': errors.name }"
        >
        <p x-show="errors.name" x-text="errors.name" class="text-red-500 text-sm mt-1"></p>
      </div>

      <div>
        <label for="email" class="block text-gray-300 text-sm font-medium mb-2">{{ trans('profile.form.email') }}</label>
        <input
          type="email"
          id="email"
          name="email"
          x-model="form.email"
          @blur="validateField('email')"
          required
          class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
          :class="{ 'border-red-500': errors.email }"
        >
        <p x-show="errors.email" x-text="errors.email" class="text-red-500 text-sm mt-1"></p>
      </div>

      <div>
        <label for="phone" class="block text-gray-300 text-sm font-medium mb-2">{{ trans('profile.form.phone') }}</label>
        <input
          type="tel"
          id="phone"
          name="phone"
          value="{{ old('phone', $user->phone) }}"
          class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
        >
        <p x-show="errors.phone" x-text="errors.phone" class="text-red-500 text-sm mt-1"></p>
      </div>

      <div>
        <label for="insta_account" class="block text-gray-300 text-sm font-medium mb-2">{{ trans('profile.form.insta_account') }}</label>
        <input
          type="text"
          id="insta_account"
          name="insta_account"
          x-model="form.insta_account"
          class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
          :class="{ 'border-red-500': errors.insta_account }"
          placeholder="{{ trans('profile.form.insta_placeholder') }}"
        >
        <p x-show="errors.insta_account" x-text="errors.insta_account" class="text-red-500 text-sm mt-1"></p>
      </div>
    </div>

    <div>
      <label for="city_id" class="block text-gray-300 text-sm font-medium mb-2">{{ trans('profile.form.city') }}</label>
      <select
        id="city_id"
        name="city_id"
        x-model="form.city_id"
        @change="validateField('city_id')"
        class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
        :class="{ 'border-red-500': errors.city_id }"
      >
        <option value="">{{ trans('profile.form.select_city') }}</option>
        @foreach(getCities() as $city)
          <option value="{{ (string) $city['id'] }}">
            {{ $city['value'] }}
          </option>
        @endforeach
      </select>
      <p x-show="errors.city_id" x-text="errors.city_id" class="text-red-500 text-sm mt-1"></p>
    </div>

    <div>
      <label for="address" class="block text-gray-300 text-sm font-medium mb-2">{{ trans('profile.form.address') }}</label>
      <textarea
        id="address"
        name="address"
        x-model="form.address"
        rows="3"
        class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition resize-none"
        :class="{ 'border-red-500': errors.address }"
      ></textarea>
      <p x-show="errors.address" x-text="errors.address" class="text-red-500 text-sm mt-1"></p>
    </div>

    <button
      type="submit"
      :disabled="loading"
      class="bg-orange-500 w-full md:w-auto cursor-pointer hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-orange-500/50 transition-all duration-300"
      :class="{ 'opacity-60 cursor-not-allowed': loading }"
    >
      <span x-show="!loading">{{ trans('profile.form.update_profile') }}</span>
      <span x-show="loading">{{ trans('profile.form.updating') }}</span>
    </button>
  </form>
</div>
