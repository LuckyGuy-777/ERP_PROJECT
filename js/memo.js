// HTML 요소 선택
const parent = document.querySelector('html');
const sectionInput = parent.querySelector('.section-input');
const inputTitle = parent.querySelector('.input-title');
const inputContent = parent.querySelector('.input-content');
const inputBtnContainer = parent.querySelector('.input-btn-container');
const btnPalette = parent.querySelector('.btn-palette');
const colorPicker = parent.querySelector('.color-picker');
const btnRefresh = parent.querySelector('.btn-refresh');
const btnDone = parent.querySelector('.btn-done');

const sectionDisplay = parent.querySelector('.section-display');

const editModal = parent.querySelector('#edit-modal');
const editModalBox = parent.querySelector('#edit-modal-box');
const editTitle = parent.querySelector('#edit-title');
const editContent = parent.querySelector('#edit-content');
const btnEditColor = parent.querySelector('#btn-edit-color');
const btnEditRefresh = parent.querySelector('#btn-edit-refresh');
const btnEditDone = parent.querySelector('#btn-edit-done');
const btnEditClose = parent.querySelector('#btn-edit-close');

/* 입력된 줄 수에 맞춰 textarea 크기 조절 */
const textArea = parent.querySelectorAll('textarea');
textArea.forEach(item =>{
  item.addEventListener('input', ()=>{
    item.style.height = 'auto';
    item.style.height = item.scrollHeight + 'px';
  })
})


parent.addEventListener('click', (e)=>{
  /* 메모 작성 선택 시 확장 */
  if(e.target === inputContent){
    inputTitle.style.display = 'block';
    inputBtnContainer.style.display = 'flex';
    colorPicker.style.display = 'none';
  }
  /* 배경선택버튼 선택시 */
  else if(e.target === btnPalette){
    colorPicker.style.display = 'flex';
    const pos = btnPalette.getBoundingClientRect();
    colorPicker.style.left= pos.x - 150 +'px';
    colorPicker.style.top = pos.y + 40 +'px';
  }

  /*메모 초기화 버튼 선택 시, 입력된 값 초기화*/
  else if(e.target === btnRefresh){
    resetInputText();
    colorPicker.style.display = 'none';
    resetColorPicker();
  }

  /*color-picker 선택 시*/
  else if(e.target === colorPicker || e.target.id.startsWith("btn-color-")){
    if(e.target !== colorPicker){
      if(inputBtnContainer.style.display === 'flex')
        sectionInput.style.backgroundColor = getComputedStyle(e.target).backgroundColor;
      else
        editModalBox.style.backgroundColor = getComputedStyle(e.target).backgroundColor;
      for(c of colorPicker.children){
        if(c.classList.contains('color-selected')){
          c.classList.remove('color-selected');
        }
        else if(c === e.target){
          c.classList.add('color-selected');
        }
      }
      colorPicker.style.display = 'none';
    }
  }

  /* done 버튼 선택 시*/
  else if(e.target === btnDone){
    if(inputTitle.value === "" && inputContent.value === ""){
      swal({
        text: "빈 메모는 만들 수 없습니다!",
        icon: "info"
      })
    }
    else{
      saveMemo(inputTitle.value, inputContent.value, getComputedStyle(sectionInput).backgroundColor);
      inputTitle.style.display = 'none';
      resetInputText();
      inputBtnContainer.style.display = 'none';
      colorPicker.style.display = 'none';
      resetColorPicker();
    }
  }

  else if(e.target === btnEditClose){
    editModal.style.visibility = "hidden";
  }

  /* edit 배경 변경 선택 시*/
  else if(e.target === btnEditColor){
    colorPicker.style.display = 'flex';
    const pos = btnEditColor.getBoundingClientRect();
    colorPicker.style.left = pos.x - 150 +'px';
    colorPicker.style.top = pos.y + 40 +'px';
    colorPicker.style.position = 'fixed';
  }
  /* edit 새로고침 선택 시 */
  else if(e.target === btnEditRefresh){
    resetEditText();
    colorPicker.style.display = 'none';
    resetColorPicker();
  }

  /* edit done 선택 시 */
  else if(e.target === btnEditDone){
    if(editTitle.value === "" && editContent.value === ""){
      swal({
        text: "빈 메모는 만들 수 없습니다!",
        icon: "info"
      })
    }
    else{
      editMemo(editTitle.value, editContent.value, getComputedStyle(editModalBox).backgroundColor, editModalBox.dataset.memoNumber);
      editModal.style.visibility = "hidden";
      colorPicker.style.display = 'none';
      resetColorPicker();
    }
  }
  /*section-input의 내부요소 선택 시, color-picker만 사라짐*/
  else if(e.target.className && sectionInput.querySelector('.'+e.target.className)){
    colorPicker.style.display = 'none';
  }

  /*section-input 및 color-picker 외부요소 선택 시, 둘다 사라짐*/
  else{
    inputTitle.style.display = 'none';
    resetInputText();
    inputBtnContainer.style.display = 'none';
    colorPicker.style.display = 'none';
    resetColorPicker();
  }

  /* 수정 모달 영역 외 선택 시, 모달 삭제*/
  if(e.target === editModal){
    swal({
      text: "메모를 수정하지 않고 나가겠습니까?",
      buttons: ["취소", "확인"],
    }).then(v=>{if(v) editModal.style.visibility = "hidden";});
  }
})

/* 화면 크기 변화 시 color-picker 지우기 및 메모 재렌더링*/
window.addEventListener('resize',function(){
  if(colorPicker.style.display !== 'none'){
    colorPicker.style.display = 'none';
  }
  render();
})
// 함수: resetInputText
// 입력 필드 초기화 함수
const resetInputText = () => {
  // 입력 필드 값 비우기
  inputContent.value = "";
  inputTitle.value = "";
  // 입력 필드 높이 초기화
  inputTitle.style.height = 'auto';
  inputContent.style.height = 'auto';
}

// 함수: resetEditText
// 편집 필드 초기화 함수
const resetEditText = () => {
  // 편집 필드 값 비우기
  editContent.value = "";
  editTitle.value = "";
  // 편집 필드 높이 초기화
  editContent.style.height = 'auto';
  editTitle.style.height = 'auto';
}
// 함수: resetColorPicker
// 컬러 피커 초기화 함수
const resetColorPicker = ()=>{
  // 모든 컬러 선택 클래스 제거
  for(c of colorPicker.children){
    if(c.classList.contains('color-selected')){
      c.classList.remove('color-selected');
    }
  }
  // 첫 번째 컬러를 선택 클래스로 추가
  colorPicker.children[0].classList.add('color-selected');
  // 섹션 입력 배경색을 흰색으로 설정
  sectionInput.style.backgroundColor = 'white';
  // 컬러 피커 위치를 절대 위치로 설정
  colorPicker.style.position = 'absolute';
}
// 함수: saveMemo
// 메모 저장 함수
const saveMemo = (title, content, bc) => {
  // 로컬 스토리지에 새로운 메모 저장
  const n = localStorage.length / 3;
  localStorage.setItem("title"+ n, title);
  localStorage.setItem("content"+ n, content);
  localStorage.setItem("bc"+ n, bc);
  // 렌더링 함수 호출
  render();
};

// 함수: editMemo
// 메모 편집 함수
const editMemo = (title, content, bc, n) => {
  // 로컬 스토리지에서 메모 편집
  localStorage.setItem("title"+ n, title);
  localStorage.setItem("content"+ n, content);
  localStorage.setItem("bc"+ n, bc);
  // 렌더링 함수 호출
  render();
}

// 함수: deleteMemo
// 메모 삭제 함수
const deleteMemo = (n) => {
  // 로컬 스토리지에서 메모 삭제
  const len = localStorage.length/3;
  n = Number(n);
  for(let i=n+1; i<len; i++){
    // 이후 메모들을 한 칸씩 앞으로 이동
    let tempTitle = localStorage.getItem("title" + i);
    let tempContent = localStorage.getItem("content" + i);
    let tempBc = localStorage.getItem("bc" + i);
    console.log(tempTitle,tempContent,tempBc, i);
    localStorage.setItem("title"+(i-1), tempTitle);
    localStorage.setItem("content"+(i-1), tempContent);
    localStorage.setItem("bc"+(i-1), tempBc);
  }
  // 마지막 메모 삭제
  localStorage.removeItem("title" + (len - 1));
  localStorage.removeItem("content" + (len - 1));
  localStorage.removeItem("bc" + (len - 1));
  // 렌더링 함수 호출
  render();
}

// 함수: render
// 메모를 화면에 렌더링하는 함수

const render = () => {
  // 섹션 디스플레이 초기화
  sectionDisplay.innerHTML = "";
  // 로컬 스토리지에 메모가 있는 경우
  if(localStorage.length !== 0){
    let memoCnt = localStorage.length / 3;
    // 메모를 역순으로 렌더링
    for(let n = memoCnt - 1; n >= 0; n--){
      // 메모 아이템과 관련 요소들 생성
      let memoItem = document.createElement('div');
      let memoTitle = document.createElement('p');
      let memoContent = document.createElement('p');
      let btnDeleteMemo = document.createElement('button');
      // 메모 제목과 내용 설정
      memoTitle.textContent = localStorage.getItem("title"+ n);
      memoContent.textContent = localStorage.getItem("content"+ n);
      let bc = localStorage.getItem("bc" + n);
      // 메모 제목이 비어있지 않으면 스타일 및 속성 설정
      if(memoTitle.textContent !== "")
        memoTitle.setAttribute("style", "font-weight: 600; margin-bottom: 10px");
      memoItem.setAttribute("style", "background-color:" + bc);
      memoItem.setAttribute("class", "display-memo");
      memoItem.setAttribute("data-memo-number", n)
      btnDeleteMemo.setAttribute("class", "btn-delete-memo");
      // 메모 아이템에 자식 요소 추가
      memoItem.appendChild(memoTitle);
      memoItem.appendChild(memoContent);
      memoItem.appendChild(btnDeleteMemo);
      // 섹션 디스플레이에 메모 아이템 추가
      sectionDisplay.appendChild(memoItem);
    }
  }
  else{
    // 메모가 없는 경우 안내 메시지 추가
    const messageEmptyMemo = document.createElement('p');
    messageEmptyMemo.textContent = "[메모장이 비어있습니다]";
    messageEmptyMemo.setAttribute("style", "font-size: 1.5rem; margin:100px auto 0px auto; color: #cccccc;");
    sectionDisplay.appendChild(messageEmptyMemo);
  }
  /*메모가 렌더링 될때마다 이벤트 리스너추가*/
  const memos = document.querySelectorAll('.display-memo');
  memos.forEach(item =>{
    item.addEventListener('click', (e)=>{
      /*메모 삭제 */
      if(e.target.className === 'btn-delete-memo'){
        swal({
          text: "해당 메모를 삭제하겠습니까??",
          buttons: ["취소", "확인"],
        }).then(v=>{
          if(v){
            deleteMemo(item.dataset.memoNumber);
          }
        });
      }
      else{
        // 메모 수정 모달 표시
        const memoNumber = item.dataset.memoNumber;
        editModal.style.visibility = "visible";
        // 편집 모달에 메모 정보 설정
        editTitle.value = localStorage.getItem("title"+memoNumber);
        editTitle.style.height = 'auto';
        editTitle.style.height = editTitle.scrollHeight + 'px';

        editContent.value = localStorage.getItem("content"+memoNumber);
        editContent.style.height = 'auto';
        editContent.style.height = editContent.scrollHeight + 'px';

        editModalBox.style.backgroundColor = localStorage.getItem("bc"+memoNumber);

        editModalBox.setAttribute('data-memo-number', memoNumber);
      }
    });

    // 메모 호버 이벤트
    item.addEventListener('mouseover', (e)=>{
      item.children[2].style.visibility = "visible";
    })
    item.addEventListener('mouseout', (e)=>{
      item.children[2].style.visibility = "hidden";
    })
  })
};
// 윈도우 스크롤 이벤트
window.onscroll = () => {
  const btnTopScroll = parent.querySelector('#btn-top-scroll');
  // 스크롤 위치에 따라 "위로 가기" 버튼 표시/숨김
  if(document.body.scrollTop > 20 || document.documentElement.scrollTop > 20){
    btnTopScroll.style.display = 'block';
  }
  else{
    btnTopScroll.style.display = 'none';
  }
}

// 함수: toTopScroll
// 페이지 상단으로 스크롤하는 함수
const toTopScroll = () => {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}
// render 함수 초기 호출
render();