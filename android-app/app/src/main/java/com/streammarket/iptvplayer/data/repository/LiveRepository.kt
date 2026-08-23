package com.streammarket.iptvplayer.data.repository

import com.streammarket.iptvplayer.data.ApiResult
import com.streammarket.iptvplayer.data.model.Category
import com.streammarket.iptvplayer.data.model.Channel
import com.streammarket.iptvplayer.data.model.EpgProgram
import com.streammarket.iptvplayer.data.remote.RetrofitClient
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext

class LiveRepository {

    suspend fun categories(): ApiResult<List<Category>> = withContext(Dispatchers.IO) {
        safeCall { RetrofitClient.service().liveCategories().let { it.body()?.categories ?: emptyList() } }
    }

    suspend fun streams(categoryId: String?): ApiResult<List<Channel>> = withContext(Dispatchers.IO) {
        safeCall { RetrofitClient.service().liveStreams(categoryId).let { it.body()?.streams ?: emptyList() } }
    }

    suspend fun epg(streamId: String): ApiResult<List<EpgProgram>> = withContext(Dispatchers.IO) {
        safeCall { RetrofitClient.service().epg(streamId).let { it.body()?.epg ?: emptyList() } }
    }

    suspend fun streamUrl(streamId: String): ApiResult<String> = withContext(Dispatchers.IO) {
        safeCall {
            val response = RetrofitClient.service().streamUrl(streamId)
            response.body()?.url ?: throw IllegalStateException("Respuesta vacía del servidor")
        }
    }

    private inline fun <T> safeCall(block: () -> T): ApiResult<T> {
        return try {
            ApiResult.Success(block())
        } catch (e: Exception) {
            ApiResult.Error(e.message ?: "Error de red")
        }
    }
}
