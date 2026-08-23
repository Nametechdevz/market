package com.streammarket.iptvplayer.ui.player

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.streammarket.iptvplayer.data.ApiResult
import com.streammarket.iptvplayer.data.model.EpgProgram
import com.streammarket.iptvplayer.data.repository.LiveRepository
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch

data class PlayerUiState(
    val loading: Boolean = true,
    val streamUrl: String? = null,
    val epg: List<EpgProgram> = emptyList(),
    val error: String? = null
)

class PlayerViewModel(
    private val liveRepository: LiveRepository,
    private val streamId: String,
    val channelName: String
) : ViewModel() {

    private val _uiState = MutableStateFlow(PlayerUiState())
    val uiState: StateFlow<PlayerUiState> = _uiState

    init {
        loadStream()
        loadEpg()
    }

    private fun loadStream() {
        viewModelScope.launch {
            when (val result = liveRepository.streamUrl(streamId)) {
                is ApiResult.Success -> _uiState.value = _uiState.value.copy(loading = false, streamUrl = result.data)
                is ApiResult.Error -> _uiState.value = _uiState.value.copy(loading = false, error = result.message)
            }
        }
    }

    private fun loadEpg() {
        viewModelScope.launch {
            when (val result = liveRepository.epg(streamId)) {
                is ApiResult.Success -> _uiState.value = _uiState.value.copy(epg = result.data)
                is ApiResult.Error -> Unit // La EPG es informativa; si falla, simplemente no se muestra.
            }
        }
    }
}
