package com.streammarket.iptvplayer.data.local

import android.content.Context
import androidx.datastore.preferences.core.edit
import androidx.datastore.preferences.core.stringPreferencesKey
import androidx.datastore.preferences.preferencesDataStore
import com.streammarket.iptvplayer.BuildConfig
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.first
import kotlinx.coroutines.flow.map

private val Context.dataStore by preferencesDataStore(name = "iptv_session")

class SessionManager(private val context: Context) {

    private object Keys {
        val TOKEN = stringPreferencesKey("token")
        val USERNAME = stringPreferencesKey("username")
        val BASE_URL = stringPreferencesKey("base_url")
    }

    val tokenFlow: Flow<String?> = context.dataStore.data.map { it[Keys.TOKEN] }
    val baseUrlFlow: Flow<String> = context.dataStore.data.map { it[Keys.BASE_URL] ?: BuildConfig.API_BASE_URL }

    suspend fun currentToken(): String? = tokenFlow.first()
    suspend fun currentBaseUrl(): String = baseUrlFlow.first()

    suspend fun saveSession(token: String, username: String) {
        context.dataStore.edit { prefs ->
            prefs[Keys.TOKEN] = token
            prefs[Keys.USERNAME] = username
        }
    }

    suspend fun saveBaseUrl(url: String) {
        val normalized = if (url.endsWith("/")) url else "$url/"
        context.dataStore.edit { prefs -> prefs[Keys.BASE_URL] = normalized }
    }

    suspend fun clear() {
        context.dataStore.edit { prefs ->
            prefs.remove(Keys.TOKEN)
            prefs.remove(Keys.USERNAME)
        }
    }
}
