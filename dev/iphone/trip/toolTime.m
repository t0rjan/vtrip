//
//  toolTime.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-19.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "toolTime.h"

@implementation toolTime

+ (NSDate *)int2nsdate:(int)timestamp
{
    NSString *str=[NSString stringWithFormat:@"%d" , timestamp];
    NSTimeInterval time=[str doubleValue];
    NSDate *detaildate=[NSDate dateWithTimeIntervalSince1970:time];
    return detaildate;
}
+ (NSString *)int2Ymd:(int)timestamp
{
    NSDateFormatter *dateFormater = [[NSDateFormatter alloc] init];
    [dateFormater setDateFormat:@"yyyy-MM-dd"];
    NSString *dateS = [dateFormater stringFromDate:[self int2nsdate:timestamp]];
    return dateS;
}

@end
