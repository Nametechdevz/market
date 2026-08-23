package com.streammarket.iptvplayer.data.remote

import com.streammarket.iptvplayer.data.model.CategoriesResponse
import com.streammarket.iptvplayer.data.model.EpgResponse
import com.streammarket.iptvplayer.data.model.LoginRequest
import com.streammarket.iptvplayer.data.model.LoginResponse
import com.streammarket.iptvplayer.data.model.StreamUrlResponse
import com.streammarket.iptvplayer.data.model.StreamsResponse
import com.streammarket.iptvplayer.data.model.UserInfo
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.POST
import retrofit2.http.Path
import retrofit2.http.Query

interface ApiService {

    @POST("api/auth/login")
    suspend fun login(@Body body: LoginRequest): Response<LoginResponse>

    @POST("api/auth/logout")
    suspend fun logout(): Response<Unit>

    @GET("api/profile")
    suspend fun profile(): Response<UserInfo>

    @GET("api/live/categories")
    suspend fun liveCategories(): Response<CategoriesResponse>

    @GET("api/live/streams")
    suspend fun liveStreams(@Query("category_id") categoryId: String? = null): Response<StreamsResponse>

    @GET("api/epg")
    suspend fun epg(@Query("stream_id") streamId: String, @Query("limit") limit: Int = 4): Response<EpgResponse>

    @GET("api/stream/live/{stream_id}")
    suspend fun streamUrl(@Path("stream_id") streamId: String, @Query("ext") ext: String = "ts"): Response<StreamUrlResponse>
}
