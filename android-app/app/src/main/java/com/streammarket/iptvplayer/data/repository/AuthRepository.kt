package com.streammarket.iptvplayer.data.repository

import android.os.Build
import com.google.gson.Gson
import com.streammarket.iptvplayer.data.ApiResult
import com.streammarket.iptvplayer.data.local.SessionManager
import com.streammarket.iptvplayer.data.model.ApiError
import com.streammarket.iptvplayer.data.model.LoginRequest
import com.streammarket.iptvplayer.data.model.LoginResponse
import com.streammarket.iptvplayer.data.remote.RetrofitClient
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext

class AuthRepository(private val sessionManager: SessionManager) {

    suspend fun restoreSession() {
        RetrofitClient.updateBaseUrl(sessionManager.currentBaseUrl())
        RetrofitClient.token = sessionManager.currentToken()
    }

    suspend fun login(baseUrl: String, username: String, password: String): ApiResult<LoginResponse> =
        withContext(Dispatchers.IO) {
            try {
                sessionManager.saveBaseUrl(baseUrl)
                RetrofitClient.updateBaseUrl(baseUrl)

                val device = "${Build.MANUFACTURER} ${Build.MODEL}".trim()
                val response = RetrofitClient.service().login(LoginRequest(username, password, device))

                if (response.isSuccessful && response.body() != null) {
                    val body = response.body()!!
                    RetrofitClient.token = body.token
                    sessionManager.saveSession(body.token, body.user.username)
                    ApiResult.Success(body)
                } else {
                    ApiResult.Error(parseError(response.errorBody()?.string()))
                }
            } catch (e: Exception) {
                ApiResult.Error(e.message ?: "No se pudo conectar con el servidor")
            }
        }

    suspend fun logout() {
        withContext(Dispatchers.IO) {
            try {
                RetrofitClient.service().logout()
            } catch (_: Exception) {
                // Si falla la llamada de red igual limpiamos la sesión local.
            }
        }
        RetrofitClient.token = null
        sessionManager.clear()
    }

    private fun parseError(raw: String?): String {
        if (raw.isNullOrBlank()) return "Error desconocido"
        return try {
            Gson().fromJson(raw, ApiError::class.java).error
        } catch (_: Exception) {
            raw
        }
    }
}
