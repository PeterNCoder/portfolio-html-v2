'use strict';

const game = {
    isRunning: false,
    //Screen
    currentScreen: '#splash-screen',
    //Splash Buttons
    playBtn: $('#play-btn'),
    //Game Buttons
    resetBtn: $('#reset-btn'),
    quitBtn: $('#quit-btn'),
    //GameOver Buttons
    // playAgainBtn: $('#play-again-btn'),
    // quitBtn: $('#quit-btn'),
    //Header Buttons
    headerQuitBtn: $('#header-quit-btn'),
    helpBtn: $('#help-btn'),
    //Modals
    setupModal: $('#setup-modal'),
    gameoverModal: $('#gameover-modal'),
    gameplayModal: $('#gameplay-modal'),
    closeModalBtn: $('#close-modal-btn'),
    //Board
    blockSize: 25,
    rows: 20,
    cols: 20,
    canvas: $('#canvas')[0],
    ctx: canvas.getContext('2d'),
    gameoverDisplay: $('#gameover-display'),
    //Difficulty
    difficultyList: $('#difficulty-list'),
    difficulty: null,
    //Snake Head
    snakeX: null,
    snakeY: null,
    //Snake Body
    snakeBody: [],
    //Food
    foodX: null,
    foodY: null,
    //Snake Moving
    velocityX: 0,
    velocityY: 0,
    gameOver: false,
    //Score
    highScore: 0,
    score: 0,
    //Player
    playerSetup: $('#player-setup'),
    playerName: '',
    playerJoinBtn: $('#player-join-btn'),
    changeNameBtn: $('#change-name-btn'),

    //Loop
    loop: null,


// Manipulation

toggleGameRunning: () => {
    game.isRunning = !game.isRunning;
    if (game.isRunning == true) {
        game.loop = setInterval(game.update, 1000/game.difficulty);
    } else {
        clearInterval(game.loop);
    }
},

switchScreen: (screenToShow) => {
    game.currentScreen = (screenToShow);
    $('.screen').hide();
    $(screenToShow).show();
    if (game.difficulty == null) {
        game.playBtn.attr('disabled', true);
    }
    if(screenToShow === '#game-screen') {
        game.headerQuitBtn.removeAttr('hidden');
    } else {
        game.headerQuitBtn.attr('hidden', true);
    } 
    
    if (game.isRunning) {
        game.toggleGameRunning();
    }
},

makeBoard: () => {
    game.canvas.height = game.rows * game.blockSize;
    game.canvas.width = game.cols * game.blockSize;
    $('.score-bg').css('width', `${game.canvas.width}`);
    game.gameoverDisplay.css({
        'width': `${game.canvas.width}`,
        'height': `${game.canvas.height}`

    })
},

drawBoard: () => {
    game.ctx.fillStyle = 'black';
    game.ctx.fillRect(0, 0, game.canvas.width, game.canvas.height);
},

makeSnake: () => {
    game.snakeX = game.blockSize * 5;
    game.snakeY = game.blockSize * 5;
},

drawSnake: () => {
    game.ctx.fillStyle = 'lime';
    game.ctx.fillRect(game.snakeX, game.snakeY, game.blockSize, game.blockSize);
    game.ctx.strokeStyle = 'white';
    game.ctx.lineWidth = 2;
    game.ctx.strokeRect(game.snakeX, game.snakeY, game.blockSize, game.blockSize);
    for (let i = 0; i < game.snakeBody.length; i++) {
        game.ctx.fillRect(game.snakeBody[i][0], game.snakeBody[i][1], game.blockSize, game.blockSize);
    };
    for (let i = game.snakeBody.length-1; i > 0; i--) {
        game.snakeBody[i] = game.snakeBody[i-1];
    }
    if (game.snakeBody.length) {
        game.snakeBody[0] = [game.snakeX, game.snakeY]
    }
},

placeFood: () => {
    game.foodX = Math.floor(Math.random() * game.cols) * game.blockSize;
    game.foodY = Math.floor(Math.random() * game.rows) * game.blockSize;
},

drawFood: () => {
    game.ctx.fillStyle = 'red';
    game.ctx.fillRect(game.foodX, game.foodY, game.blockSize, game.blockSize);
},

eatFood: () => {
    if (game.snakeX == game.foodX && game.snakeY == game.foodY) {
        game.snakeBody.push([game.foodX, game.foodY]);
        game.placeFood();
        game.score += 1;
        game.updateScore();
    }
},

gameOverCheck: () => {
    if (game.snakeX < 0 || game.snakeX >= game.cols*game.blockSize || game.snakeY < 0 || game.snakeY >= game.cols*game.blockSize) {
        game.gameOver = true;
        clearInterval(game.loop);
        game.displayModal(game.gameoverModal);
        game.gameoverDisplay.attr('hidden', false);
    }

    for (let i = 0; i < game.snakeBody.length; i++) {
        if (game.snakeX == game.snakeBody[i][0] && game.snakeY == game.snakeBody[i][1]) {
            game.gameOver = true;
            clearInterval(game.loop);
            game.displayModal(game.gameoverModal);
            game.gameoverDisplay.attr('hidden', false);
        }
    }
},

update: () => {
    console.log('helo');
    if (game.gameOver == true) {
        return;
    }
    game.snakeX += game.velocityX * game.blockSize;
    game.snakeY += game.velocityY * game.blockSize;
    game.gameOverCheck();
    game.eatFood();
    game.drawBoard();
    game.drawFood();
    game.drawSnake();
},

updateScore: () => {
    $('#score').text(`${game.score}`);
    if (game.score > game.highScore) {
        game.highScore = game.score;
        $('#highscore').text(`${game.playerName}` + ' - ' + `${game.highScore}`);
    }
},

updateName: () => {
    game.playerName = $('#player-name-input').val();
    if (game.playerName.length > 0) {
    $('#player-name-display').text('Player: ' + `${game.playerName}`)
    $('#joined-setup').attr('hidden', false);
    game.playerSetup.attr('hidden', true);
    }
},

changeDirection: (e) => {
    if (game.currentScreen != '#game-screen') {
        return;
    }
    else if (e.code == "ArrowUp" && game.velocityY != 1) {
        game.velocityX = 0;
        game.velocityY = -1;
    }
    else if (e.code == "ArrowDown" && game.velocityY != -1) {
        game.velocityX = 0;
        game.velocityY = 1;
    }
    else if (e.code == "ArrowLeft" && game.velocityX != 1) {
        game.velocityX = -1;
        game.velocityY = 0;
    }
    else if (e.code == "ArrowRight" && game.velocityX != -1) {
        game.velocityX = 1;
        game.velocityY = 0;
    }
},

resetDifficulty: () => {
    $('.input').prop('checked', false);
    game.difficulty = null;
    game.gameover = true;
    game.gameOver = false;
},

resetGame: () => {
    game.gameOver = false;
    clearInterval(game.loop);
    game.isRunning = true;
    game.snakeBody = [];
    game.velocityX = 0;
    game.velocityY = 0;
    game.score = 0;
    game.updateScore();
    game.ctx.clearRect(0, 0, game.canvas.width, game.canvas.height);
    game.placeFood();
    game.makeSnake();
    game.drawFood();
    game.drawSnake();
    game.gameoverDisplay.attr('hidden', true);
    game.loop = setInterval(game.update, 1000/game.difficulty);

},

// Modals

displayModal: (modalToShow) => {
    if (modalToShow === '#game-screen') {
        game.gameplayModal.modal('show');
    } else if (modalToShow === '#splash-screen') {
        game.setupModal.modal('show');
    } else if (modalToShow === game.gameoverModal) {
        game.gameoverModal.modal('show');
    }
        if (game.isRunning === true) {
        game.toggleGameRunning();
        game.wasRunning = true;
    } 
},

closeModal: () => {
    if (game.wasRunning === true) {
        game.toggleGameRunning();
        game.wasRunning = false;
    }
},

// Init
init: () => {

    $('#body').keydown((e) => {
        game.changeDirection(e);
    });
    game.difficultyList.click((e) => {
        game.playBtn.attr('disabled', false)    
        if (e.target.matches("input[id='snail']")) {
            game.difficulty = 2;
        } else if (e.target.matches("input[id='easy']")) {
            game.difficulty = 3.5;
        } else if (e.target.matches("input[id='normal']")) {
            game.difficulty = 5;
        } else if (e.target.matches("input[id='hard']")) {
            game.difficulty = 15;
        } else if (e.target.matches("input[id='insane']")) {
            game.difficulty = 25;
        }
    });

    game.playerJoinBtn.click((e) => {    
        game.updateName();
    });

    game.changeNameBtn.click((e) => {    
        game.playerSetup.attr('hidden', false);
        game.playerJoinBtn.text('Change');
    });

    game.playBtn.click((e) => {    
        game.switchScreen('#game-screen');
        game.toggleGameRunning();
    });
    game.resetBtn.click((e) => {    
        game.resetGame();
    });
    game.quitBtn.click((e) => {    
        game.resetGame();
        game.resetDifficulty();
        game.switchScreen('#splash-screen');
    });

    game.helpBtn.click((e) => {    
        game.displayModal(game.currentScreen);
    });

    game.closeModalBtn.click((e) => {    
        game.closeModal();
    });

    game.switchScreen('#splash-screen');
    game.makeBoard();
    game.makeSnake();
    game.placeFood();
    game.update();

},
}

game.init();