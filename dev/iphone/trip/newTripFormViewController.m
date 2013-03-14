//
//  newTripViewController.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-8.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#define cgrectSum CGRectMake(10, (CGFloat)startHeight, (CGFloat)width, (CGFloat)lastHeight)

#import "newTripFormViewController.h"

@interface newTripFormViewController ()

@property (assign , nonatomic) int _totalHeight;
@property (assign , nonatomic) int _titleMaxLen;
@property (assign , nonatomic) int _titleMinLen;

@property (retain , nonatomic) mlViewCustomNotice *notice;
@property (retain , nonatomic) mlViewCustomActionSheet *pickerPad_1;
@property (retain , nonatomic) mlViewCustomActionSheet *pickerPad_2;


@property (retain , nonatomic) UILabel *tripTitleLen;




- (void)render;

- (IBAction)showPickerPad:(id)sender;
- (IBAction)hidePickerPad:(id)sender;

@end

@implementation newTripFormViewController



@synthesize pickerPad_1;
@synthesize pickerPad_2;
@synthesize datePicker;
@synthesize daysPicker;
@synthesize tripTitle;
@synthesize tripDate;
@synthesize tripDays;
@synthesize tripWhere;
@synthesize modelMyTrip;


- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {

    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    self._titleMaxLen = 20;
    self._titleMinLen = 6;
    self._totalHeight = 460;
    
    self.view.backgroundColor = [UIColor yellowColor];

    self.notice = [[mlViewCustomNotice alloc] init];
        self.noticeLabel = [[UILabel alloc] initWithFrame:CGRectMake(10, 10, 200, 200)];
        self.noticeLabel.text = @"xxxxx";
        self.noticeLabel.textAlignment = UITextAlignmentCenter;
        self.noticeLabel.textColor = [UIColor redColor];
        self.noticeLabel.font = [UIFont systemFontOfSize:22];
        self.noticeLabel.backgroundColor = [UIColor clearColor];
        [self.notice addSubview:self.noticeLabel];
    
    self.pickerPad_1 = [[mlViewCustomActionSheet alloc] initWithHeight:216];
    self.pickerPad_2 = [[mlViewCustomActionSheet alloc] initWithHeight:216];



    UITapGestureRecognizer *tapGesture = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(resignInput)];
    [self.view addGestureRecognizer:tapGesture];

    [self render];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma action

- (BOOL)isAllInputAvailable
{
    int c = [toolString strWidth:self.tripTitle.text];
    if(c < self._titleMinLen)
    {
        self.noticeLabel.text = @"给旅行起个名字吧！";
        [self.notice showInView:self.view forSecond:1];
        return NO;
    }
    else if (c > self._titleMaxLen)
    {
        self.noticeLabel.text = @"名字太长了～";
        [self.notice showInView:self.view forSecond:1];
        return NO;
    }
    return YES;
}
#pragma view
- (void)render
{
    int startHeight = 0;
    int lastHeight = 45;
    int spaceHeight = 5;
    int extSpaceHeight = 10;
    int width = 300;
    
    //title field
    startHeight += (lastHeight + spaceHeight);
    lastHeight = 30;
    self.tripTitle = [[UITextField alloc] initWithFrame:CGRectMake(10, (CGFloat)startHeight, 260, (CGFloat)lastHeight)];
    tripTitle.delegate = self;
    tripTitle.borderStyle = UITextBorderStyleBezel;
    tripTitle.placeholder = @"给旅行起个名字吧～";
    [tripTitle addTarget:self action:@selector(titleValueChanged) forControlEvents:UIControlEventValueChanged];
    [self.view addSubview:tripTitle];
    //title length
    self.tripTitleLen = [[UILabel alloc] initWithFrame:CGRectMake(270, (CGFloat)startHeight, 50, (CGFloat)lastHeight)];
    self.tripTitleLen.text = [NSString stringWithFormat:@"%d" , self._titleMaxLen];
    self.tripTitleLen.textAlignment = UITextAlignmentCenter;
    [self.view addSubview:self.tripTitleLen];
    
    startHeight += extSpaceHeight;
    
    //when
    startHeight += (lastHeight + spaceHeight);
    lastHeight = 30;
    UILabel *whenTitle = [[UILabel alloc] initWithFrame:CGRectMake(10, (CGFloat)startHeight, (CGFloat)width, (CGFloat)lastHeight)];
    whenTitle.text = @"出发时间：";
    
    self.tripDate = [[UILabel alloc] initWithFrame:CGRectMake(0, 0, 100, 30)];
    tripDate.backgroundColor = [UIColor clearColor];
    tripDate.textAlignment = UITextAlignmentCenter;
    NSDateFormatter *dateFormater = [[NSDateFormatter alloc] init];
    [dateFormater setDateFormat:@"yyyy-MM-dd"];
    NSString *dateS = [dateFormater stringFromDate:[NSDate date]];
    tripDate.text = [NSString stringWithFormat:@"%@" , dateS];
    
    UIButton *whenBtn = [UIButton buttonWithType:UIButtonTypeRoundedRect];
    whenBtn.frame = CGRectMake(100, (CGFloat)startHeight, 100, (CGFloat)lastHeight);
    whenBtn.tag = 1;
    [whenBtn addTarget:self action:@selector(showPickerPad:) forControlEvents:UIControlEventTouchUpInside];
    [whenBtn addSubview:tripDate];
    [self.view addSubview:whenTitle];
    [self.view addSubview:whenBtn];

    
    //when picker;
    self.datePicker = [[UIDatePicker alloc] initWithFrame:CGRectMake(0, 0, 320, 216)];
    [datePicker setDatePickerMode:UIDatePickerModeDate];
    datePicker.tag = 1;
    [datePicker addTarget:self action:@selector(endPickDate) forControlEvents:UIControlEventValueChanged];
    [pickerPad_1 addSubview:datePicker];
    
    //days
    startHeight += (lastHeight + spaceHeight);
    lastHeight = 30;
    UILabel *daysTitle = [[UILabel alloc] initWithFrame:CGRectMake(10, (CGFloat)startHeight, (CGFloat)width, (CGFloat)lastHeight)];
    daysTitle.text = @"去几天？";
    [self.view addSubview:daysTitle];
    
    self.tripDays = [[UILabel alloc] initWithFrame:CGRectMake(0, 0, 100, 30)];
    tripDays.backgroundColor = [UIColor clearColor];
    tripDays.textAlignment = UITextAlignmentCenter;
    tripDays.text = @"1";
    UIButton *daysBtn = [UIButton buttonWithType:UIButtonTypeRoundedRect];
    daysBtn.frame = CGRectMake(100, (CGFloat)startHeight, 100, (CGFloat)lastHeight);
    daysBtn.tag = 2;
    [daysBtn addTarget:self action:@selector(showPickerPad:) forControlEvents:UIControlEventTouchUpInside];
    [daysBtn addSubview:tripDays];
    [self.view addSubview:daysBtn];
    UILabel *daysD = [[UILabel alloc] initWithFrame:CGRectMake(202, (CGFloat)startHeight, 30, (CGFloat)lastHeight)];
    daysD.text = @"天";
    [self.view addSubview:daysD];
    
    //days picker
    daysPicker = [[UIPickerView alloc] initWithFrame:CGRectMake(0, 0, 320, 216)];
    daysPicker.dataSource = self;
    daysPicker.delegate = self;
    daysPicker.tag = 2;
    daysPicker.showsSelectionIndicator = YES;

    [pickerPad_2 addSubview:daysPicker];
    

    
    
    startHeight += extSpaceHeight;
    
    //where
    startHeight += (lastHeight + spaceHeight);
    lastHeight = 30;
    self.tripWhere = [[UITextField alloc] initWithFrame:CGRectMake(10, (CGFloat)startHeight, 200, (CGFloat)lastHeight)];
    tripWhere.placeholder = @"去哪？";
    tripWhere.delegate = self;
    tripWhere.borderStyle = UITextBorderStyleBezel;
    //[self.view addSubview:tripWhere];

}

- (IBAction)showPickerPad:(id)sender
{
    for (UIView *v in self.view.subviews) {
        [v resignFirstResponder];
    }
    UIButton *btn = (UIButton *)sender;
    
    if (btn.tag == 1) {
        [self.pickerPad_1 showInView:self.view];
    }
    else
    {
        [self.pickerPad_2 showInView:self.view];
    }
}
#pragma pickview
- (NSInteger)pickerView:(UIPickerView *)pickerView numberOfRowsInComponent:(NSInteger)component
{
    return 20;
}
- (NSInteger)numberOfComponentsInPickerView:(UIPickerView *)pickerView
{
    return 1;
}
- (UIView *)pickerView:(UIPickerView *)pickerView viewForRow:(NSInteger)row forComponent:(NSInteger)component reusingView:(UIView *)view
{
    UILabel *lb = [[UILabel alloc] initWithFrame:CGRectMake(0, 0, 100, 30)];
    lb.backgroundColor = [UIColor clearColor];
    lb.textAlignment = UITextAlignmentCenter;
    lb.font = [UIFont boldSystemFontOfSize:24];
    if(row != 19)
    {
        lb.text = [NSString stringWithFormat:@"%d天" , row+1];
        return lb;
    }
    else
    {
        lb.text = @"20天以上";
        return lb;
    }
}
- (void)pickerView:(UIPickerView *)pickerView didSelectRow:(NSInteger)row inComponent:(NSInteger)component
{
    
    int n = [pickerView selectedRowInComponent:0];
    tripDays.text = [NSString stringWithFormat:@"%d" , n+1];
    
}
- (void)endPickDate
{
    NSDate *date = [datePicker date];
    NSDateFormatter *dateFormater = [[NSDateFormatter alloc] init];
    [dateFormater setDateFormat:@"yyyy-MM-dd"];
    NSString *dateS = [dateFormater stringFromDate:date];
    self.tripDate.text = dateS;
}



#pragma input
- (IBAction)resignInput
{
    for (UIView *v in self.view.subviews) {
        
        [v resignFirstResponder];
    }
    [[NSNotificationCenter defaultCenter] removeObserver:self name:UITextFieldTextDidChangeNotification object:nil];
    [self.pickerPad_1 hideAll];
}
- (void)textFieldDidBeginEditing:(UITextField *)textField
{
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(titleValueChanged) name:UITextFieldTextDidChangeNotification object:nil];
    [self.pickerPad_1 hideAll];
}
- (void)titleValueChanged
{
    int c = [toolString strWidth:self.tripTitle.text];
    self.tripTitleLen.text = [NSString stringWithFormat:@"%d" , (self._titleMaxLen - (NSInteger)c)];
    if (c > self._titleMaxLen) {
        self.tripTitleLen.textColor = [UIColor redColor];
    }else{
        self.tripTitleLen.textColor = [UIColor blackColor];
    }
    
}




@end
