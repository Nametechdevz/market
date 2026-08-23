package com.streammarket.iptvplayer.ui.home

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.streammarket.iptvplayer.data.ApiResult
import com.streammarket.iptvplayer.data.model.Category
import com.streammarket.iptvplayer.data.model.Channel
import com.streammarket.iptvplayer.data.repository.LiveRepository
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch

data class HomeUiState(
    val loadingCategories: Boolean = false,
    val loadingChannels: Boolean = false,
    val categories: List<Category> = emptyList(),
    val selectedCategoryId: String? = null,
    val channels: List<Channel> = emptyList(),
    val searchQuery: String = "",
    val error: String? = null
) {
    val filteredChannels: List<Channel>
        get() = if (searchQuery.isBlank()) {
            channels
        } else {
            channels.filter { it.name.contains(searchQuery, ignoreCase = true) }
        }
}

class HomeViewModel(private val liveRepository: LiveRepository) : ViewModel() {

    private val _uiState = MutableStateFlow(HomeUiState())
    val uiState: StateFlow<HomeUiState> = _uiState

    init {
        loadCategories()
    }

    fun loadCategories() {
        _uiState.value = _uiState.value.copy(loadingCategories = true, error = null)
        viewModelScope.launch {
            when (val result = liveRepository.categories()) {
                is ApiResult.Success -> {
                    val categories = result.data
                    _uiState.value = _uiState.value.copy(
                        loadingCategories = false,
                        categories = categories
                    )
                    categories.firstOrNull()?.let { selectCategory(it.category_id) }
                }
                is ApiResult.Error -> _uiState.value = _uiState.value.copy(
                    loadingCategories = false,
                    error = result.message
                )
            }
        }
    }

    fun selectCategory(categoryId: String) {
        _uiState.value = _uiState.value.copy(selectedCategoryId = categoryId, loadingChannels = true, error = null)
        viewModelScope.launch {
            when (val result = liveRepository.streams(categoryId)) {
                is ApiResult.Success -> _uiState.value = _uiState.value.copy(
                    loadingChannels = false,
                    channels = result.data
                )
                is ApiResult.Error -> _uiState.value = _uiState.value.copy(
                    loadingChannels = false,
                    error = result.message
                )
            }
        }
    }

    fun onSearchQueryChange(query: String) {
        _uiState.value = _uiState.value.copy(searchQuery = query)
    }
}
