package com.streammarket.iptvplayer.ui.player

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.material3.CircularProgressIndicator
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.DisposableEffect
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.runtime.remember
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.viewinterop.AndroidView
import androidx.media3.common.MediaItem
import androidx.media3.exoplayer.ExoPlayer
import androidx.media3.ui.PlayerView
import com.streammarket.iptvplayer.data.model.EpgProgram
import com.streammarket.iptvplayer.ui.theme.Surface

@Composable
fun PlayerScreen(viewModel: PlayerViewModel) {
    val state by viewModel.uiState.collectAsState()
    val context = LocalContext.current

    val exoPlayer = remember {
        ExoPlayer.Builder(context).build()
    }

    DisposableEffect(state.streamUrl) {
        state.streamUrl?.let { url ->
            exoPlayer.setMediaItem(MediaItem.fromUri(url))
            exoPlayer.prepare()
            exoPlayer.playWhenReady = true
        }
        onDispose { }
    }

    DisposableEffect(Unit) {
        onDispose { exoPlayer.release() }
    }

    Box(modifier = Modifier.fillMaxSize()) {
        AndroidView(
            factory = {
                PlayerView(it).apply {
                    player = exoPlayer
                    useController = true
                }
            },
            modifier = Modifier.fillMaxSize()
        )

        if (state.loading) {
            CircularProgressIndicator(modifier = Modifier.align(Alignment.Center))
        }

        state.error?.let {
            Text(
                text = it,
                color = MaterialTheme.colorScheme.error,
                modifier = Modifier.align(Alignment.Center).padding(24.dp)
            )
        }

        EpgPanel(
            channelName = viewModel.channelName,
            epg = state.epg,
            modifier = Modifier.align(Alignment.TopStart)
        )
    }
}

@Composable
private fun EpgPanel(channelName: String, epg: List<EpgProgram>, modifier: Modifier = Modifier) {
    Column(
        modifier = modifier
            .fillMaxWidth(0.4f)
            .background(Surface.copy(alpha = 0.85f))
            .padding(16.dp)
    ) {
        Text(channelName, style = MaterialTheme.typography.titleMedium)
        if (epg.isNotEmpty()) {
            LazyColumn(modifier = Modifier.padding(top = 8.dp)) {
                items(epg) { program ->
                    val prefix = if (program.now_playing) "▶ " else ""
                    Text(
                        text = "$prefix${program.title}",
                        style = MaterialTheme.typography.bodyMedium,
                        modifier = Modifier.padding(vertical = 4.dp)
                    )
                }
            }
        }
    }
}
