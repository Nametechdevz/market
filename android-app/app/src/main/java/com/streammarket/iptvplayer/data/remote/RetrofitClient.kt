package com.streammarket.iptvplayer.data.remote

import com.streammarket.iptvplayer.BuildConfig
import okhttp3.Interceptor
import okhttp3.OkHttpClient
import okhttp3.Response
import okhttp3.logging.HttpLoggingInterceptor
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory

/**
 * Punto único de acceso a la API. `baseUrl` y `token` se actualizan desde
 * SessionManager al iniciar la app y al hacer login/logout.
 */
object RetrofitClient {

    @Volatile
    var baseUrl: String = BuildConfig.API_BASE_URL
        private set

    @Volatile
    var token: String? = null

    @Volatile
    private var cachedService: ApiService? = null
    private var cachedBaseUrl: String? = null

    private val authInterceptor = Interceptor { chain ->
        val request = chain.request().newBuilder()
        token?.let { request.addHeader("Authorization", "Bearer $it") }
        chain.proceed(request.build())
    }

    private fun buildClient(): OkHttpClient {
        val logging = HttpLoggingInterceptor().apply {
            level = if (BuildConfig.DEBUG) HttpLoggingInterceptor.Level.BODY else HttpLoggingInterceptor.Level.NONE
        }
        return OkHttpClient.Builder()
            .addInterceptor(authInterceptor)
            .addInterceptor(logging)
            .build()
    }

    fun updateBaseUrl(newBaseUrl: String) {
        baseUrl = if (newBaseUrl.endsWith("/")) newBaseUrl else "$newBaseUrl/"
    }

    fun service(): ApiService {
        val current = cachedService
        if (current != null && cachedBaseUrl == baseUrl) {
            return current
        }
        val retrofit = Retrofit.Builder()
            .baseUrl(baseUrl)
            .client(buildClient())
            .addConverterFactory(GsonConverterFactory.create())
            .build()
        val service = retrofit.create(ApiService::class.java)
        cachedService = service
        cachedBaseUrl = baseUrl
        return service
    }
}
