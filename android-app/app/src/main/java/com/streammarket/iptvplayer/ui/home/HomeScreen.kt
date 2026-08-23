package com.streammarket.iptvplayer.ui.home

import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.fillMaxHeight
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.grid.GridCells
import androidx.compose.foundation.lazy.grid.LazyVerticalGrid
import androidx.compose.foundation.lazy.grid.items
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.CircularProgressIndicator
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.OutlinedTextField
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.unit.dp
import coil.compose.AsyncImage
import com.streammarket.iptvplayer.data.model.Category
import com.streammarket.iptvplayer.data.model.Channel
import com.streammarket.iptvplayer.ui.theme.Surface
import com.streammarket.iptvplayer.ui.theme.SurfaceVariant

@Composable
fun HomeScreen(
    viewModel: HomeViewModel,
    onChannelClick: (Channel) -> Unit
) {
    val state by viewModel.uiState.collectAsState()

    Row(modifier = Modifier.fillMaxSize()) {
        CategorySidebar(
            categories = state.categories,
            selectedId = state.selectedCategoryId,
            onSelect = viewModel::selectCategory,
            modifier = Modifier.width(220.dp).fillMaxHeight()
        )

        Column(modifier = Modifier.fillMaxSize().padding(16.dp)) {
            OutlinedTextField(
                value = state.searchQuery,
                onValueChange = viewModel::onSearchQueryChange,
                label = { Text("Buscar canal") },
                modifier = Modifier.fillMaxWidth()
            )

            state.error?.let {
                Text(it, color = MaterialTheme.colorScheme.error, modifier = Modifier.padding(top = 12.dp))
            }

            if (state.loadingChannels) {
                Box(modifier = Modifier.fillMaxSize(), contentAlignment = Alignment.Center) {
                    CircularProgressIndicator()
                }
            } else {
                ChannelGrid(channels = state.filteredChannels, onChannelClick = onChannelClick)
            }
        }
    }
}

@Composable
private fun CategorySidebar(
    categories: List<Category>,
    selectedId: String?,
    onSelect: (String) -> Unit,
    modifier: Modifier = Modifier
) {
    LazyColumn(
        modifier = modifier.background(Surface),
        contentPadding = androidx.compose.foundation.layout.PaddingValues(vertical = 12.dp)
    ) {
        items(categories) { category ->
            val selected = category.category_id == selectedId
            Text(
                text = category.category_name,
                modifier = Modifier
                    .fillMaxWidth()
                    .background(if (selected) SurfaceVariant else Surface)
                    .clickable { onSelect(category.category_id) }
                    .padding(horizontal = 16.dp, vertical = 12.dp)
            )
        }
    }
}

@Composable
private fun ChannelGrid(channels: List<Channel>, onChannelClick: (Channel) -> Unit) {
    LazyVerticalGrid(
        columns = GridCells.Adaptive(minSize = 140.dp),
        horizontalArrangement = Arrangement.spacedBy(12.dp),
        verticalArrangement = Arrangement.spacedBy(12.dp),
        modifier = Modifier.fillMaxSize().padding(top = 16.dp)
    ) {
        items(channels) { channel ->
            ChannelTile(channel = channel, onClick = { onChannelClick(channel) })
        }
    }
}

@Composable
private fun ChannelTile(channel: Channel, onClick: () -> Unit) {
    Column(
        modifier = Modifier
            .fillMaxWidth()
            .clip(RoundedCornerShape(12.dp))
            .background(Surface)
            .clickable(onClick = onClick)
            .padding(12.dp)
    ) {
        AsyncImage(
            model = channel.stream_icon,
            contentDescription = channel.name,
            contentScale = ContentScale.Fit,
            modifier = Modifier
                .fillMaxWidth()
                .height(70.dp)
        )
        Text(
            text = channel.name,
            style = MaterialTheme.typography.bodyMedium,
            maxLines = 2,
            modifier = Modifier.padding(top = 8.dp)
        )
    }
}
