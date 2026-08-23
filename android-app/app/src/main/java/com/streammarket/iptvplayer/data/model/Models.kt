package com.streammarket.iptvplayer.data.model

data class LoginRequest(
    val username: String,
    val password: String,
    val device_info: String
)

data class LoginResponse(
    val token: String,
    val user: UserInfo
)

data class UserInfo(
    val id: Int,
    val username: String,
    val expires_at: String?,
    val max_connections: Int
)

data class ApiError(
    val error: String
)

data class CategoriesResponse(
    val categories: List<Category>
)

// Los campos id llegan como número o texto según el panel Xtream de origen;
// se declaran String para que Gson los normalice sin fallar.
data class Category(
    val category_id: String,
    val category_name: String,
    val parent_id: String? = null
)

data class StreamsResponse(
    val streams: List<Channel>
)

data class Channel(
    val stream_id: String,
    val name: String,
    val stream_icon: String? = null,
    val category_id: String? = null,
    val epg_channel_id: String? = null,
    val num: String? = null
)

data class EpgResponse(
    val epg: List<EpgProgram>
)

data class EpgProgram(
    val title: String,
    val description: String,
    val start: String?,
    val end: String?,
    val now_playing: Boolean
)

data class StreamUrlResponse(
    val url: String
)
